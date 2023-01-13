<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConferenceController extends Controller
{
    public function index()
    {
        $conferences = Conference::paginate(15);
        return view('conferences.index', compact(['conferences']));
    }

    public function create()
    {
        $countries = Country::all();
        return view('conferences.create', compact('countries'));
    }

    public function store(ConferenceRequest $request)
    {
        $data = $request->validated();
        $country = request()->country;
        $conference = Conference::create($data);
        Country::associateCountry($conference, $country);
        User::associateUser($conference);
        User::givePermissions();
        return redirect()->route('conferences.index');
    }

    public function show(Conference $conference)
    {
        return view('conferences.show', compact(['conference']));
    }

    protected function isAllowed($conference): bool
    {
        return $conference->user_id === Auth::id() || Auth::user()->hasRole('Admin');
    }

    public function edit(Conference $conference)
    {
        if (!$this->isAllowed($conference)) {
            abort(403);
        }
        $countries = Country::all();
        return view('conferences.edit', compact(['conference', 'countries']));
    }

    public function update(Conference $conference, ConferenceRequest $request)
    {
        if (!$this->isAllowed($conference)) {
            abort(403);
        }
        $data = $request->validated();
        $country = request()->country;
        $conference->update($data);
        Country::associateCountry($conference, $country);
        return redirect()->route('conferences.show', $conference->id);
    }

    public function destroy(Conference $conference)
    {
        if (!$this->isAllowed($conference)) {
            abort(403);
        }
        $conference->delete();
        return redirect()->route('conferences.index');
    }
}
