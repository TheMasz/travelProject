<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PrefController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'member.auth'], function () {
    Route::get('/', [MainController::class, 'index']);
    Route::get('/basket', [MainController::class, 'basket']);
    Route::get('/province/{province_id}', [MainController::class, 'province']);
    Route::get('/province/{province_id}/{location_id}', [MainController::class, 'locationDetail']);
    Route::get('/plans/navigative', [MainController::class, 'navigative']);

    
    Route::post('addPref', [PrefController::class, 'addPref']);
    Route::post('/api/getLocations', [LocationsController::class, 'getLocations']);
});

Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/test', [AdminController::class, 'test']);
});

Route::group(['middleware' => 'login.auth'], function () {
    Route::get('/signin', [AuthController::class, 'signin']);
    Route::get('/signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
  
});
Route::get("/logout", [AuthController::class, 'logout']);


Route::get('/testdatabase', function () {
    $result = DB::table('members')->get();
    return $result;
});
