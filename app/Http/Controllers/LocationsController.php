<?php

namespace App\Http\Controllers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\LocationTypes;
use App\Models\Preferences;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function getLocations(Request $request)
    {
        $receivedArray = $request->all();
        $locations = [];
        foreach ($receivedArray as $location_id) {
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



            if ($location) {
                $locations[] = $location;
            }
        }
        return $locations;
    }
}
