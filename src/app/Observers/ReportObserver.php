<?php

namespace App\Observers;

use App\Mail\AdminDeleteReport;
use App\Mail\UpdateReportTime;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReportObserver
{

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        if ($report->start_time != $report->getOriginal('start_time') ||
            $report->end_time != $report->getOriginal('end_time')) {

            $conference = $report->conference;
            $joinedUsers = $conference->users;

            foreach ($joinedUsers as $joinedUser) {
                if ($joinedUser->hasRole('Listener')) {
                    Mail::to($joinedUser->email)->send(new UpdateReportTime($conference, $report, $report->user));
                }
            }
        }
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        if (Auth::user()->hasRole('Admin')) {
            $report->user->joinedConferences()->detach($report->conference);
            Mail::to($report->user->email)->send(new AdminDeleteReport($report->conference));
        }
    }
}