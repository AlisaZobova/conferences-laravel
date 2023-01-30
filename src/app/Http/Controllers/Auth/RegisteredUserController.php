<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);

        $user->assignRole($request->type);

        event(new Registered($user));

        return $user;
    }

    public function store_additional(Request $request, User $user)
    {
        $request->validate([
            'firstname' => 'string|max:255|required',
            'lastname' => 'string|max:255|required',
            'phone' => 'string|max:20|required',
            'birthdate' => 'required|date|before_or_equal:' . now(),
            'country' => 'required|integer'
        ]);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->birthdate =  $request->birthdate;
        $user->phone = $request->phone;

        Country::associateCountry($user, $request->country);

        Auth::login($user);

        return $user;
    }

}
