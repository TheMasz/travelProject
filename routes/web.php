<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PlanController;
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
    Route::get('/myplans', [MainController::class, 'myplans']);

    
    Route::post('/api/addPref', [PrefController::class, 'addPref']);

    Route::post('/api/getLocations', [LocationsController::class, 'getLocations']);

    Route::post('/api/addPlan', [PlanController::class, 'addPlan']);
    Route::delete('/api/removePlan', [PlanController::class, 'removePlan']);

});

Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/locations', [AdminController::class, 'locations']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/reviews', [AdminController::class, 'reviews']);
    Route::get('/admin/preferences', [AdminController::class, 'preferences']);
    Route::get('/admin/test', [AdminController::class, 'test']);
    Route::get('admin/deletelocation{id}', [AdminController::class, 'deletelocation'])->name('deletelocation');
    Route::get('admin/searchlocation', [AdminController::class, 'searchlocation'])->name('search.location');
    Route::get('admin/deleteusers{member_id}', [AdminController::class, 'deleteusers'])->name('deleteusers');
    Route::get('admin/searchusers', [AdminController::class, 'searchusers'])->name('search.users');
    Route::post('admin/insertlocations', [AdminController::class, 'store'])->name('insertlocations');
    Route::post('admin/insertpreference', [AdminController::class, 'insertpreference'])->name('insertpreference');
    Route::get('admin/delpreferences{preference_id}', [AdminController::class, 'delpreferences'])->name('delpreferences');
    Route::get('admin/searchpre', [AdminController::class, 'searchpre'])->name('search.pre');
    Route::get('admin/searchreviews', [AdminController::class, 'searchreviews'])->name('search.reviews');
    Route::get('admin/delreviews{comment_id}', [AdminController::class, 'delreviews'])->name('delreviews');
    // Route::get('admin/editpreferences{preference_id}', [AdminController::class, 'edit'])->name('edit-preference');
    Route::put('admin/updatepreferences/{preference_id}', [AdminController::class, 'updatepreference'])->name('update-preference');
    Route::put('admin/updateusers/{member_id}', [AdminController::class, 'updateusers'])->name('update-users');
    Route::get('admin/locations/{location_id}', [AdminController::class, 'locationsMore'])->name('locations'); 
    Route::get('admin/reviews/{location_id}/{comment_id}', [AdminController::class, 'reviews_more'])->name('reviews'); 
    // Route::post('admin/updatephoto/{img_id}', [AdminController::class, 'updatephoto'])->name('updatephoto');
    Route::put('admin/updatephoto/{img_id}', [AdminController::class, 'updatephoto'])->name('updatephoto');
    Route::put('admin/updateLocation/{location_id}', [AdminController::class, 'updateLocation'])->name('updateLocation');



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
