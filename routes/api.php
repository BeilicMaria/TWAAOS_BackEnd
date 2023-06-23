<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CertificateController;
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
Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::post('logout', [LogoutController::class, 'logout']);
        //..............................USER............................
        Route::get('users/{page?}/{per_page?}/{order?}/{filter?}', [UsersController::class, 'index']);
        Route::get('user/{id}', [UsersController::class, 'get']);
        Route::get('getStaffData', [UsersController::class, 'getStaffData']);
        Route::post('addOrUpdateStaff', [UsersController::class, 'addOrUpdateStaff']);
        Route::post('importUsers', [UsersController::class, 'importUsers']);
        Route::post('deleteUsers', [UsersController::class, 'delete']);
        Route::post('user', [UsersController::class, 'addOrUpdateStudent']);
        Route::get('reset', [UsersController::class, 'resetData']);

        //..............................ROLE............................
        Route::get('roles', [RolesController::class, 'index']);
        Route::get('role/{id}', [RolesController::class, 'get']);
        //..............................Faculty............................
        Route::post('faculty', [FacultyController::class, 'put']);
        Route::get('faculties', [FacultyController::class, 'index']);
        //..............................Domains............................
        Route::post('domains', [DomainController::class, 'put']);
        Route::get('domains', [DomainController::class, 'index']);
        Route::delete('domain/{id}', [DomainController::class, 'delete']);
        //..............................Programs............................
        Route::post('programs', [ProgramController::class, 'put']);
        Route::get('programs', [ProgramController::class, 'index']);
        Route::delete('program/{id}', [ProgramController::class, 'delete']);
        //.............................. Certificates............................
        Route::post('cerificate', [CertificateController::class, 'post']);
        Route::get('cerificate/{id}', [CertificateController::class, 'get']);
        Route::get('certificates/{page?}/{per_page?}/{order?}/{filter?}', [CertificateController::class, 'index']);
        Route::post('rejectCertificate', [CertificateController::class, 'rejectCertificate']);
        Route::post('aproveCerificate', [CertificateController::class, 'aproveCerificate']);
    }

);
