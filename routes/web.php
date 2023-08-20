<?php

use App\Http\Controllers\AuthController;
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


Route::get('/', [MainController::class,'index']);

Route::get('/signin', [AuthController::class, 'signin']);
Route::get('/signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get("/logout", [AuthController::class, 'logout']);

Route::post('addPref', [PrefController::class, 'addPref']);

Route::get('/testdatabase', function () {
    $result = DB::table('members')->get();
    return $result;
});