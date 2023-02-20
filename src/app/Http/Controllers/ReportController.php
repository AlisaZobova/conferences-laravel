<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Report;
use DateTime;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with('comments');

        if ($request->query('from')) {
            $result = [];
            foreach ($reports->get() as $report) {
                $start = substr($report->start_time, 11);
                if ($start >= $request->query('from')) {
                    array_push($result, $report->id);
                }
            }
            $reports = $reports->whereIn('id', $result);
        }
        if ($request->query('to')) {
            $result = [];
            foreach ($reports->get() as $report) {
                $end = substr($report->end_time, 11);
                if ($end <= $request->query('to')) {
                    array_push($result, $report->id);
                }
            }
            $reports = $reports->whereIn('id', $result);
        }
        if ($request->query('duration')) {
            $result = [];
            $range = explode('-', $request->query('duration'));
            foreach ($reports->get() as $report) {
                $end = new DateTime($report->end_time);
                $start = new DateTime($report->start_time);
                $timeDiff = $end->diff($start);
                $minutes = $timeDiff->h * 60 + $timeDiff->i;
                if ($minutes >= $range[0] && $minutes <= $range[1]) {
                    array_push($result, $report->id);
                }
            }
            $reports = $reports->whereIn('id',  $result);
        }
        if ($request->query('category')) {
            $categories = explode(',', $request->query('category'));
            $reports = $reports->whereIn('category_id', $categories);
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

        return $report->load('user', 'conference', 'comments', 'category');
    }

    public function show(Report $report)
    {
        return $report->load('user', 'conference', 'comments', 'category');
    }

    public function update(Report $report, ReportRequest $request)
    {
        $data = $request->validated();

        if ($data['presentation']) {
            if ($report->presentation) {
                $filename = public_path('upload') . '\\' . $report->presentation;
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
        return $report->load('user', 'conference', 'comments', 'category');
    }

    public function destroy(Report $report)
    {
        $report->delete();
    }

    public function download(Report $report)
    {
        $file = public_path('upload') . '\\' . $report->presentation;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $headers = [
            'Content-Type' => $ext === '.ppt' ? 'application/vnd.ms-powerpoint' : 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
        return \response()->download($file, $report->presentation, $headers);
    }
}
