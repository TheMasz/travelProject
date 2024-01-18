<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\Members;
use App\Models\PersonalPreference;
use App\Models\PlansTrip;
use App\Models\Preferences;
use App\Models\Provinces;
use App\Models\Reviews;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    function index()
    {
        $member_id = session('member_id');
        $preferencesCount = PersonalPreference::where('member_id', $member_id)->count();
        $preferences = Preferences::get();
        $mypreferences = false;
        if ($preferencesCount == 0) {
            $mypreferences = true;
        }
        $getLocationsByPref = App::make('getLocationsByPref');
        $locations = $getLocationsByPref(null, $member_id, []);

        if (is_array($locations) && count($locations) > 12) {
            $locations = array_slice($locations, 0, 12);
        }

        return view('main.index')->with([
            'mypreferences' => $mypreferences,
            'preferences' => $preferences,
            'locations' => $locations
        ]);
    }

    function province($province_id)
    {
        $province_id = (int)$province_id;
        $member_id = session('member_id');
        $province_name = Provinces::where('province_id', $province_id)->first();
        $getLocationsByPref = App::make('getLocationsByPref');

        $selectedPreferences = session('selectedPreferences', []);
        // dd($selectedPreferences);

        $locations = $getLocationsByPref($province_id, $member_id, $selectedPreferences);
        $countLocations = count($locations);
        $allPrefs = Preferences::all();

        $perPage = 8;
        $page = request()->has('page') ? request()->query('page') : 1;
        $paginatedLocations = array_slice($locations, ($page - 1) * $perPage, $perPage);
        return view('main.province')->with(
            [
                'province_id' => $province_id,
                'province_name' => $province_name['province_name'],
                'paginatedLocations' => $paginatedLocations,
                'member_id' => $member_id, 'page' => $page,
                'perPage' => $perPage,
                'countLocations' => $countLocations,
                'allPrefs' => $allPrefs,
                'selectedPreferences' => $selectedPreferences,
            ]
        );
    }

    function basket()
    {
        return view('main.basket');
    }
    function navigative()
    {
        return view('main.navigative');
    }
    function locationDetail($province_id, $location_id)
    {
        $locationFunction = App::make('getLocation');
        $location = $locationFunction($location_id);
        $reviewsFunction = App::make('getReviews');
        $reviews = $reviewsFunction($location_id)->take(3);
        return view('main.locationDetail')->with([
            'location_detail' => $location,
            'reviews' => $reviews
        ]);
    }
    function myplans()
    {
        $member_id = session('member_id');
        $myplans = PlansTrip::select('plan_name')
            ->selectRaw('GROUP_CONCAT(location_id SEPARATOR ", ") AS locations_id')
            ->where('member_id', $member_id)
            ->groupBy('plan_name')
            ->get();

        return view('main.myplans')->with(['myplans' => $myplans]);
    }
    function profile()
    {
        $member_id = session('member_id');
        $myPreferences = PersonalPreference::select('personal_preference.member_id', 'personal_preference.preference_id', 'personal_preference.score', 'preferences.preference_name')
            ->join('preferences', 'preferences.preference_id', '=', 'personal_preference.preference_id')
            ->where('personal_preference.member_id', $member_id)
            ->get();
        $reviewsFunction = App::make('getMyReviews');
        $myReviews = $reviewsFunction('desc')->take(6);
        $countPlans = PlansTrip::count();
        $countReviews = Reviews::count();
        $member = Members::where('member_id', $member_id)
            ->select('email', 'username', 'member_img')
            ->first();
        return view('main.profile')->with([
            'preferences' => $myPreferences,
            'reviews' => $myReviews,
            'countPlans' => $countPlans,
            'countReviews' => $countReviews,
            'member' => $member
        ]);
    }

    function about()
    {
        return view('main.about');
    }
}
