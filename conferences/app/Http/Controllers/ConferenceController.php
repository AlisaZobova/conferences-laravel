<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use App\Models\Country;

class ConferenceController extends Controller
{
    public function index() {
        $conferences = Conference::all();
        return view('conferences.index', compact('conferences'));
    }

    public function create() {
        $countries = Country::all();;
        return view('conferences.create', compact('countries'));
    }

    public function store(ConferenceRequest $request) {
        $data = $request->validated();;
        $country = request()->country;
        $conference = Conference::create($data);
        Country::associateCountry($conference, $country);
        return redirect()->route('conferences.index');
    }

    public function show(Conference $conference) {
        return view('conferences.show', compact('conference'));
    }

    public function edit(Conference $conference){
        $countries = Country::all();
        return view('conferences.edit', compact(['conference', 'countries']));
    }

    public function update(Conference $conference, ConferenceRequest $request) {
        $data = $request->validated();
        $country = request()->country;
        $conference->update($data);
        Country::associateCountry($conference, $country);
        return redirect()->route('conferences.show', $conference->id);
    }

    public function destroy(Conference $conference) {
        $conference->delete();
        return redirect()->route('conferences.index');
    }
}
