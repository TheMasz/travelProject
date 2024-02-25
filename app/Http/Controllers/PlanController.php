<?php

namespace App\Http\Controllers;

use App\Models\PlansTrip;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function addPlan(Request $request)
    {
        $planName = $request->input('plan_name');
        $member_id = $request->input('member_id');
        $location_id = $request->input('location_id');
        $locations = explode(',', $location_id);
        foreach ($locations as $location) {
            $plan = new PlansTrip();
            $plan->location_id = $location;
            $plan->member_id = $member_id;
            $plan->plan_name = $planName;
            $plan->save();
        }
        return response()->json(['success' => true]);
    }

    public function removePlan(Request $request,$planName)
    {
        // $planName = $request->input('plan_name');
        $deleted = PlansTrip::where('plan_name', $planName)->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['message' => 'Plan not found or could not be removed'], 404);
        }
    }
}
