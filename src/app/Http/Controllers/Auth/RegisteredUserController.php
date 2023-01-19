<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = Country::all();
        return view('auth.register', compact('countries'));
    }

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

        return $user->id;
    }

    public function store_additional(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'firstname' => 'string|max:255|nullable',
            'lastname' => 'string|max:255|nullable',
            'phone' => 'string|max:20|nullable',
            'birthdate' => 'nullable|date|before_or_equal:' . now()
        ]);

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->birthdate =  $request->birthdate;
        $user->phone = $request->phone;

        if ($request->country)
        {
            Country::associateCountry($user, $request->country);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

}
