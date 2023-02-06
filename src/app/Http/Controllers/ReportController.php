<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return Report::with('comments')->orderBy('start_time', 'DESC')->get();
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

        return $report->load('user', 'conference', 'comments');
    }

    public function show(Report $report)
    {
        return $report->load('user', 'conference', 'comments');
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
        return $report->load('user', 'conference', 'comments');
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
