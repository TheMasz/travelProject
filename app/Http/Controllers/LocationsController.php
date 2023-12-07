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
        $locationFunction = App::make('getLocations');
        $locations = $locationFunction($receivedArray);
        return $locations;
    }
    public function checkOpening($location_id)
    {
        config(['app.timezone' => 'Asia/Bangkok']);
        date_default_timezone_set(config('app.timezone'));
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
