<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use App\Models\Country;
use App\Models\User;

class ConferenceController extends Controller
{
    public function index()
    {
        return Conference::orderBy('conf_date', 'DESC')->paginate(15);
    }


    public function store(ConferenceRequest $request)
    {
        $data = $request->validated();
        $country = request()->country;
        $conference = Conference::create($data);
        Country::associateCountry($conference, $country);
        User::associateUser($conference);
        User::givePermissions();
        return $conference->load('country', 'reports', 'category');
    }

    public function show(Conference $conference)
    {
        return $conference->load('country', 'reports', 'category');
    }

    public function update(Conference $conference, ConferenceRequest $request)
    {
        $data = $request->validated();
        $country = $request->country_id;
        $conference->update($data);
        Country::associateCountry($conference, $country);
        return $conference->load('country', 'reports', 'category');
    }

    public function destroy(Conference $conference)
    {
        $conference->delete();
    }
}
