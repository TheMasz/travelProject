<?php

namespace App\Http\Controllers;

use App\Models\LocationImages;
use App\Models\Locations;
use App\Models\LocationTypes;
use App\Models\Preferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocationsController extends Controller
{
    public function getLocations(Request $request)
    {
        $receivedArray = $request->all();
        $getLocations = App::make('getLocations');
        $locations = $getLocations($receivedArray);
        return $locations;
    }

    public function getNearLocations(Request $request)
    {
        $receivedArray = $request->all();
        $getLocations = App::make('getLocations');
        $locations = $getLocations($receivedArray);
        $excludeLocationIds = array_column($locations, 'location_id');

        $province_ids = array_unique(array_column($locations, 'province_id'));
        $locationsByProvince = [];
        $locationsProvince = Locations::select(
            'locations.location_id',
            'locations.location_name',
            'locations.latitude',
            'locations.longitude',
            'locations.province_id',
            'locations.address',
            LocationImages::raw('GROUP_CONCAT(DISTINCT location_images.img_path SEPARATOR ", ") AS Images')
        )
            ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
            ->whereIn('locations.province_id', $province_ids)
            ->groupBy(
                'locations.location_id',
                'locations.location_name',
                'locations.latitude',
                'locations.longitude',
                'locations.province_id',
                'locations.address'
            )
            ->get();

        foreach ($locationsProvince as $location) {
            if (!in_array($location->location_id, $excludeLocationIds)) {
                $locationsByProvince[$location->province_id][] = [
                    'location_id' => $location->location_id,
                    'location_name' => $location->location_name,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'Images' => $location->Images,
                    'address' => $location->address
                ];
            }
        }

        $locationsNearest = [];
        $addedLocationIds = [];

        foreach ($locations as $location1) {
            foreach ($locationsByProvince[$location1['province_id']] as $location2) {
                if ((int)$location1['location_id'] != (int)$location2['location_id']) {
                    $distance = calculateDistance(
                        $location1['latitude'],
                        $location1['longitude'],
                        $location2['latitude'],
                        $location2['longitude']
                    );
                    if ($distance < 5 && !in_array($location2['location_id'], $addedLocationIds)) {
                        $locationsNearest[] = $location2;
                        $addedLocationIds[] = $location2['location_id'];
                    }
                }
            }
        }

        return $locationsNearest;
    }


    public function checkOpening($location_id)
    {
        // config(['app.timezone' => 'Asia/Bangkok']);
        // date_default_timezone_set(config('app.timezone'));
        $location = Locations::select('location_id', 's_time', 'e_time')
            ->where('location_id', $location_id)
            ->first();

        if ($location) {
            $open = $location->s_time;
            $close = $location->e_time;
            $currentDateTime = now()->format('H:i:s');

            if ($currentDateTime >= $open && $currentDateTime < $close) {
                return response()->json(['status' => 'opend', 'time' => $currentDateTime]);
            } else {
                return response()->json(['status' => 'closed', 'time' => $currentDateTime]);
            }
        } else {
            return response()->json(['status' => 'location_not_found'], 404);
        }
    }
}


function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    // Radius of the Earth in kilometers
    $earthRadius = 6371;

    // Convert latitude and longitude from degrees to radians
    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    // Calculate the differences
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    // Calculate distance using Haversine formula
    $distance = 2 * $earthRadius * asin(sqrt(
        pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
    ));

    return $distance; // Distance in kilometers
}
