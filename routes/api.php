<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('existEmail',[AuthController::class, 'existEmail']);
Route::post('logout',[AuthController::class, 'logout'])->middleware('authToken');
// End Auth

Route::post('complete-user-profile',[UserController::class, 'completeUserProfile'])->middleware('authToken');
Route::post('edit-user-positions',[UserController::class, 'editUserPositions']); //->middleware('authToken');
Route::post('edit-user-days-available',[UserController::class, 'editUserDaysAvailable'])->middleware('authToken');
Route::post('edit-user-location',[UserController::class, 'editUserLocation']); //->middleware('authToken');
Route::post('get-user-data',[UserController::class, 'getUserData']); //->middleware('authToken');
Route::post('play-now/get-user-offers',[UserController::class, 'getUserOffers']); //->middleware('authToken');
Route::post('matches/create',[GameController::class, 'createMatch']); //->middleware('authToken');

