<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $request->user()->fill($data);

        $request->user()->save();

        return $request->user()->load('roles', 'conferences', 'joinedConferences', 'reports', 'favorites');
    }
}
