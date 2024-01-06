<?php

namespace App\Http\Controllers;

use App\Models\PersonalPreference;
use App\Models\Preferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrefController extends Controller
{
    function addPref(Request $req)
    {
        $arr_data = $req->input();
        $selectedPrefs = [];
        $defaultScore = 3;

        foreach ($arr_data as $key => $value) {
            if ($key !== '_token') {
                $prefix = "preference_";
                $pref_id = substr($key, strlen($prefix));
                $member_id = session('member_id');

                $selectedPrefs[$pref_id] = $value;
                $pref = new PersonalPreference();
                $pref->member_id = $member_id;
                $pref->preference_id = $pref_id;
                $pref->score = $value;
                $pref->save();
            }
        }
        $allPrefs = Preferences::all();
        foreach ($allPrefs as $pref) {
            if (!isset($selectedPrefs[$pref->preference_id])) {
                $member_id = session('member_id');
                $defaultPref = new PersonalPreference();
                $defaultPref->member_id = $member_id;
                $defaultPref->preference_id = $pref->preference_id;
                $defaultPref->score = $defaultScore;
                $defaultPref->save();
            }
        }

        return redirect("/");
    }
    function editPref(Request $req)
    {
        $arr_data = $req->input();
        $member_id = session('member_id');

        foreach ($arr_data as $key => $value) {
            if ($key !== '_token' && $key !== '_method') {
                $prefix = "preference_";
                $pref_id = substr($key, strlen($prefix));

                $affected = DB::table('personal_preference')
                    ->where('member_id', $member_id)
                    ->where('preference_id', $pref_id)
                    ->update(['score' => $value]);
            }
        }
        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลเรียบร้อย']);
    }
}
