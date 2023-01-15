<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function join(Conference $conference): \Illuminate\Http\RedirectResponse
    {
        Auth::user()->joinedConferences()->attach($conference);
        return redirect()->route('conferences.show', $conference->id);
    }

    public function cancel(Conference $conference): \Illuminate\Http\RedirectResponse
    {
        Auth::user()->joinedConferences()->detach($conference);
        return redirect()->route('conferences.index');
    }
}
