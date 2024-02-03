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
    // Route::get('/api/checkOpening/{location_id}', [LocationsController::class, 'checkOpening']);
    Route::post('/api/{province_id}/filter', [LocationsController::class, 'filterByPreferences']);

    //----------Plans--------------
    Route::post('/api/addPlan', [PlanController::class, 'addPlan']);
    Route::delete('/api/removePlan', [PlanController::class, 'removePlan']);
    Route::get('/api/clearSessionPref', [PrefController::class, 'clearSessionPref']);

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
    Route::get('/admin/locations', [AdminController::class, 'locations']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/reviews', [AdminController::class, 'reviews']);
    Route::get('/admin/preferences', [AdminController::class, 'preferences']);
    Route::get('/admin/questions', [AdminController::class, 'questions']);
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
    Route::get('admin/delreviews{review_id}', [AdminController::class, 'delreviews'])->name('delreviews');
    // Route::get('admin/editpreferences{preference_id}', [AdminController::class, 'edit'])->name('edit-preference');
    Route::put('admin/updatepreferences/{preference_id}', [AdminController::class, 'updatepreference'])->name('update-preference');
    Route::put('admin/updateusers/{member_id}', [AdminController::class, 'updateusers'])->name('update-users');
    Route::get('admin/locations/{location_id}', [AdminController::class, 'locationsMore'])->name('locations'); 
    Route::get('admin/reviews/{location_id}', [AdminController::class, 'reviews_more'])->name('reviews'); 
    // Route::post('admin/updatephoto/{img_id}', [AdminController::class, 'updatephoto'])->name('updatephoto');
    Route::put('admin/updatephoto/{img_id}', [AdminController::class, 'updatephoto'])->name('updatephoto');
    Route::put('admin/updateLocation/{location_id}', [AdminController::class, 'updateLocation'])->name('updateLocation');
    Route::get('admin/locations', [AdminController::class, 'fetchData'])->name('admin.locations');
    Route::post('admin/insertQue', [AdminController::class, 'insertQue'])->name('insertQue');
    Route::get('admin/delQue{question_id}', [AdminController::class, 'delQue'])->name('delQue');
    Route::get('admin/searchQue', [AdminController::class, 'searchQue'])->name('searchQue');
    Route::put('admin/updatequestions/{question_id}', [AdminController::class, 'updatequestions'])->name('updateQue');


});

Route::group(['middleware' => 'login.auth'], function () {
    Route::get('/signin', [AuthController::class, 'signin']);
    Route::get('/signup', [AuthController::class, 'signup']);
    Route::get('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::get('/resetPassword/checkQuestion/{email}', [AuthController::class, 'checkQuestion']);
    Route::get('/resetPassword/checkQuestion/{email}/newPassword', [AuthController::class, 'newPassword']);

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('/checkEmail', [AuthController::class, 'checkEmail']);
    Route::post('/checkQuiz', [AuthController::class, 'checkQuiz']);
    Route::post('/setNewPassword', [AuthController::class, 'setNewPassword']);
});

Route::get("/logout", [AuthController::class, 'logout']);


Route::get('/testdatabase', function () {
    $result = DB::table('members')->get();
    return $result;
});
