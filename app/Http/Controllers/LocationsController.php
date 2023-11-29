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

}
