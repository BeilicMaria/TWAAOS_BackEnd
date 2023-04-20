<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;

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

//..............................THIS ROUTES ARE FOR PASSPORT............................
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);


//Google
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

//..............................THIS ROUTES ARE FOR  AUTHENTICATED USERS............................
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LogoutController::class, 'logout']);
    //..............................USER............................
    Route::get('users/{page?}/{per_page?}/{sort?}/{order?}/{filter?}', [UsersController::class, 'index']);
    Route::get('user/{id}', [UsersController::class, 'get']);
    //..............................ROLE............................
    Route::get('roles', [RolesController::class, 'index']);
    Route::get('role/{id}', [RolesController::class, 'get']);

});
