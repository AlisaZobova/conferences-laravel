<?php

namespace App\Observers;

use App\Mail\JoinAnnouncer;
use App\Mail\JoinListener;
use App\Models\Conference;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function belongsToManyAttached($relation, User $user, $ids) {
        $conference = Conference::find($ids[0]);
        $joinedUsers = $conference->users;

        if($user->hasRole('Announcer')) {
            $report = $conference->reports->where('user_id', $user->id)->first();
            foreach ($joinedUsers as $joinedUser) {
                if($joinedUser->hasRole('Listener')) {
                    Mail::to($joinedUser->email)->send(new JoinAnnouncer($conference, $report, $user));
                }
            }
        }

        else if($user->hasRole('Listener')) {
            foreach ($joinedUsers as $joinedUser) {
                if($joinedUser->hasRole('Announcer')) {
                    Mail::to($joinedUser->email)->send(new JoinListener($conference, $user));
                }
            }
        }
    }
}
