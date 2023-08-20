<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function Dashboard()
    {
        if (!session()->has('member_id')) {
            return redirect('/signin');
        } else {
            $member = Members::where('member_id', session('member_id'))->first();
            if ($member['status'] == 'admin') {
                return view('admin.dashboard');
            }
            return redirect('/');
        }
    }
}
