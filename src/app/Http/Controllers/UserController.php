<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function join(Conference $conference)
    {
        Auth::user()->joinedConferences()->attach($conference);
        return Auth::user();
    }

    public function cancel(Conference $conference)
    {
        Auth::user()->joinedConferences()->detach($conference);
        return Auth::user();
    }
}
