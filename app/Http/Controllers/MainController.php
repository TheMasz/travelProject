<?php

namespace App\Http\Controllers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\PersonalPreference;
use App\Models\PlansTrip;
use App\Models\Preferences;
use App\Models\Provinces;
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
        return view('main.index')->with(['mypreferences' => $mypreferences, 'preferences' => $preferences]);
    }
    function province($province_id)
    {
        $province_id = (int)$province_id;
        $member_id = session('member_id');
        $province_name = Provinces::where('province_id', $province_id)->first();
        $getLocationsByPref = App::make('getLocationsByPref');;
        $locations = $getLocationsByPref($province_id, $member_id);
        $countLocations = count($locations);
        
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
                'countLocations' => $countLocations
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
        return view('main.locationDetail')->with(['location_detail' => $location]);
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
}
