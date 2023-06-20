<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProgramController;
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
Route::get('/auth', [LoginController::class, 'redirectToAuth']);
Route::get('/auth/callback', [LoginController::class, 'handleAuthCallback']);

//..............................THIS ROUTES ARE FOR  AUTHENTICATED USERS............................
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LogoutController::class, 'logout']);
    //..............................USER............................
    Route::get('users/{page?}/{per_page?}/{sort?}/{order?}/{filter?}', [UsersController::class, 'index']);
    Route::get('user/{id}', [UsersController::class, 'get']);
    //..............................ROLE............................
    Route::get('roles', [RolesController::class, 'index']);
    Route::get('role/{id}', [RolesController::class, 'get']);
    //..............................Faculty............................
    Route::post('faculty', [FacultyController::class, 'post']);
    Route::get('faculties', [FacultyController::class, 'index']);
    //..............................Domains............................
    Route::post('domains', [DomainController::class, 'post']);
    Route::get('domains', [DomainController::class, 'index']);
    //..............................Programs............................
    Route::post('programs', [ProgramController::class, 'post']);
    Route::get('programs', [ProgramController::class, 'index']);
});
