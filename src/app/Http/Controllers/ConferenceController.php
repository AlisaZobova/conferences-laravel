<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        $conferences = Conference::with('country', 'reports', 'category');

        if ($request->query('from')) {
            $from = new \DateTime($request->query('from'));
            $conferences = $conferences->whereDate('conf_date', '>=', $from);
        }
        if ($request->query('to')) {
            $to = new \DateTime($request->query('to'));
            $conferences = $conferences->whereDate('conf_date', '<=', $to);
        }
        if ($request->query('reports')) {
            $result = [];
            $range = explode('-', $request->query('reports'));
            foreach ($conferences->get() as $conference) {
                $reportsCount = count($conference->reports);
                if ($reportsCount >= intval($range[0]) && $reportsCount <= intval($range[1])) {
                    array_push($result, $conference->id);
                }
            }
            $conferences = $conferences->whereIn('id',  $result);
        }
        if ($request->query('category')) {
            $categories = explode(',', $request->query('category'));
            $conferences = $conferences->whereIn('category_id', $categories);
        }
        return $conferences->orderBy('conf_date', 'DESC')->paginate(15);
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
