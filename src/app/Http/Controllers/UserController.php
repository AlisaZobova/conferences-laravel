<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        else {
            unset($data['password']);
        }
        $request->user()->update($data);

        return $request->user()->load('roles', 'conferences', 'joinedConferences', 'reports', 'favorites');
    }

    public function join(Conference $conference)
    {
        Auth::user()->joinedConferences()->attach($conference);
    }

    public function cancel(Conference $conference)
    {
        Auth::user()->joinedConferences()->detach($conference);
    }

    public function addFavorite(Report $report)
    {
        Auth::user()->favorites()->attach($report);
    }

    public function deleteFavorite(Report $report)
    {
        Auth::user()->favorites()->detach($report);
    }

    public function getUser(User $user)
    {
        return $user->load('roles', 'conferences', 'joinedConferences', 'reports', 'favorites');
    }
}
