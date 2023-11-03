<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function Dashboard()
    {
        return view('admin.dashboard');
    }
    function Test(){
        return view('admin.test');
    }
}
