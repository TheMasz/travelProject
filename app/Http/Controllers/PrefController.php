<?php

namespace App\Http\Controllers;

use App\Models\PersonalPreference;
use Illuminate\Http\Request;

class PrefController extends Controller
{
    function addPref(Request $req)
    {
        $arr_data = $req->input();
        if(count($arr_data) > 1){
            foreach ($arr_data as $key => $value) {
                if($key!='_token'){
                    $prefix = "preference_";
                    $pref_id = substr($key, strlen($prefix));
                    $member_id = session('member_id');
                    $pref = new PersonalPreference();
                    $pref->member_id = $member_id;
                    $pref->preference_id = $pref_id;
                    $pref->score = $value;
                    $pref->save();
                }           
            }
           return redirect("/");
        }else{
            return redirect()->back()->with(['warning' => 'กรุณาเลือกความชอบ'  ])->withInput();
        }
        
    }
}
