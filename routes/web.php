<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PrefController;
use App\Http\Controllers\ReviewsController;
use App\Models\Locations;
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
    Route::get('/myplans', [MainController::class, 'myplans']);
    Route::get('/profile', [MainController::class, 'profile']);
    Route::get('/about', [MainController::class, 'about']);

    //----------Preferences--------------
    Route::post('/api/addPref', [PrefController::class, 'addPref']);
    Route::put('/api/editPref', [PrefController::class, 'editPref']);

    //----------Locations--------------
    Route::post('/api/getLocations', [LocationsController::class, 'getLocations']);
    Route::post('/api/getNearLocations', [LocationsController::class, 'getNearLocations']);
    Route::get('/api/checkOpening/{location_id}', [LocationsController::class, 'checkOpening']);
    Route::post('/api/{province_id}/filter', [LocationsController::class, 'filterByPreferences']);

    //----------Plans--------------
    Route::post('/api/addPlan', [PlanController::class, 'addPlan']);
    Route::delete('/api/removePlan', [PlanController::class, 'removePlan']);

    //Reviews
    Route::post('/api/postReview', [ReviewsController::class, 'postReview']);
    Route::get('/api/getReviews/{location_id}', [ReviewsController::class, 'getReviews']);
    Route::get('/api/getMyReviews/{sorted}', [ReviewsController::class, 'getMyReviews']);
    Route::get('/api/loadMoreReviews/{location_id}/{offset}', [ReviewsController::class, 'loadMoreReviews']);
    Route::get('/api/loadMoreMyReviews/{offset}/{sorted}', [ReviewsController::class, 'loadMoreMyReviews']);
    Route::post('/api/likeActions', [ReviewsController::class, 'likeActions']);
    Route::delete('/api/removeReview', [ReviewsController::class, 'removeReview']);

    //----------Members--------------
    Route::put('/api/editProfile', [MembersController::class, 'editProfile']);
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
