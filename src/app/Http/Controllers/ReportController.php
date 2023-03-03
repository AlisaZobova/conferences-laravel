<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Jobs\ProcessReportsExport;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with('comments');

        if (count($request->query()) > 1) {
            $reports->whereHas('conference', function ($query) {
                $query->whereDate('conf_date', '>=', date("Y-m-d"));
            });
        }

        foreach ($request->query() as $key=>$value) {
            if ($key === 'from') {
                $reports->whereTime('start_time', '>=', $value);
            }
            if ($key === 'to') {
                $reports->whereTime('end_time', '<=', $value);
            }
            if ($key === 'duration') {
                $range = explode('-', $value);
                $reports->whereRaw("TIMESTAMPDIFF(minute, start_time, end_time) BETWEEN " . $range[0] . " AND " . $range[1]);
            }
            if ($key === 'category') {
                $categories = explode(',', $value);
                $reports->whereIn('category_id', $categories);
            }
        }
        return $reports->orderBy('start_time', 'DESC')->paginate(12);
    }

    public function search(Request $request)
    {
        if ($request->query('topic')) {
            $reports = Report::whereRaw("UPPER(topic) LIKE '%". strtoupper($request->query('topic'))."%'");
            return $reports->orderBy('start_time', 'DESC')->get();
        }

        return Report::all();
    }

    public function store(ReportRequest $request)
    {
        $data = $request->validated();

        if ($data['presentation']) {
            $fileName = time() . '_' . $data['presentation']->getClientOriginalName();
            $data['presentation']->move(public_path('upload'), $fileName);
            $data['presentation'] = $fileName;
        }

        $report = Report::create($data);

        if ($request->get('online') != 'false') {
            $zoom = new ZoomMeetingController();
            $zoom->store($report);
        }

        cache()->forget('meetings');

        return $report->load('user', 'conference', 'comments', 'category', 'zoomConference');
    }

    public function show(Report $report)
    {
        return $report->load('user', 'conference', 'comments', 'category', 'zoomConference');
    }

    public function update(Report $report, ReportRequest $request)
    {
        $data = $request->validated();

        if ($data['presentation']) {
            if ($report->presentation) {
                $delimeter = PHP_OS_FAMILY === 'Windows' ? '\\' : '/';
                $filename = public_path('upload') . $delimeter . $report->presentation;
                unlink($filename);
            }
            $fileName = time() . '_' . $data['presentation']->getClientOriginalName();
            $data['presentation']->move(public_path('upload'), $fileName);
            $data['presentation'] = $fileName;
        }

        else {
            unset($data['presentation']);
        }

        $report->update($data);
        return $report->load('user', 'conference', 'comments', 'category', 'zoomConference');
    }

    public function destroy(Report $report)
    {
        $id = $report->zoomConference->id;
        $report->delete();
        $zoom = new ZoomMeetingController();
        $zoom->delete($id);
        cache()->forget('meetings');
    }

    public function download(Report $report)
    {
        $delimeter = PHP_OS_FAMILY === 'Windows' ? '\\' : '/';
        $file = public_path('upload') . $delimeter . $report->presentation;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => $ext === '.ppt' ? 'application/vnd.ms-powerpoint' : 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        return \response()->download($file, $report->presentation, $headers);
    }

    public function export() {
        ProcessReportsExport::dispatch()->delay(now()->addSeconds(5));;
    }
}
