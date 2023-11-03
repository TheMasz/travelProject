<?php

namespace App\Http\Controllers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\PersonalPreference;
use App\Models\Preferences;
use App\Models\Provinces;

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
        $member_id = session('member_id');
        $province_name = Provinces::where('province_id', $province_id)->first();
        return view('main.province')->with(['province_id' => $province_id, 'province_name' => $province_name['province_name'], 'member_id' => $member_id]);
    }
    function basket()
    {

        return view('main.basket');
    }
    function navigative()
    {
        return view('main.navigative');
    }
    function locationDetail($province_id,$location_id)
    {
        $location = Locations::select(
            'locations.location_id',
            'locations.location_name',
            'locations.address',
            'locations.detail',
            'locations.s_time',
            'locations.latitude',
            'locations.longitude',
            Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_id SEPARATOR ",") AS PrefId'),
            Preferences::raw('GROUP_CONCAT(DISTINCT preferences.preference_name SEPARATOR ", ") AS Preferences'),
            LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images'),
            'location_images.credit'
        )
            ->join('location_types', 'locations.location_id', '=', 'location_types.location_id')
            ->join('preferences', 'location_types.preference_id', '=', 'preferences.preference_id')
            ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
            ->where('locations.location_id', $location_id)
            ->groupBy(
                'locations.location_id',
                'locations.location_name',
                'locations.address',
                'locations.detail',
                'locations.s_time',
                'locations.latitude',
                'locations.longitude',
                'location_images.credit'
            )
            ->first();
        return view('main.locationDetail')->with(['location_detail' => $location]);
    }
}
