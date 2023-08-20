<?php

namespace App\Http\Controllers;

use App\Models\PersonalPreference;
use App\Models\Preferences;

class MainController extends Controller
{
    function index()
    {
        if (!session()->has('member_id')) {
            return redirect("/signin");
        }
        $member_id = session('member_id');
        $preferencesCount = PersonalPreference::where('member_id', $member_id)->count();
        $preferences = Preferences::get();
        $mypreferences = false;
        if ($preferencesCount == 0) {
            $mypreferences = true;
        }
        return view('main.index')->with(['mypreferences' => $mypreferences, 'preferences' => $preferences]);
    }
}
