<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function join(Conference $conference)
    {
        Auth::user()->joinedConferences()->attach($conference);
    }

    public function cancel(Conference $conference)
    {
        Auth::user()->joinedConferences()->detach($conference);
    }

    public function getUser(User $user) {
        return $user->load('roles', 'conferences', 'joinedConferences');
    }
}
