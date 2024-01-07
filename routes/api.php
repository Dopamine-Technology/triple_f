<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\User\Auth\AuthController;
use App\Http\Controllers\Api\User\Auth\LoginController;
use App\Http\Controllers\Api\User\Auth\RegisterController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AppController::class)->prefix('/app')->middleware(['localization'])->group(function () {
    Route::get('get_translations', 'getTranslatableStrings');
    Route::get('get_user_types', 'getUserTypes');
    Route::post('contact_us', 'contactUs');
    Route::post('latest_posts', 'getLatestPosts');
    Route::get('get_sports', 'getSports');
    Route::get('get_countries', 'getCountries');
    Route::get('get_cities/{country_id?}', 'getCities');
    Route::post('get_sport_positions/{sport}', 'getSportPositions');
});
Route::controller(RegisterController::class)->prefix('/user/auth')->middleware(['localization'])->group(function () {
    Route::post('register', 'register');
});
Route::controller(LoginController::class)->prefix('/user/auth')->middleware(['localization'])->group(function () {
    Route::post('email_login', 'loginWithEmail');
    Route::post('google_login', 'loginWithGoogle');
    Route::post('facebook_login', 'loginWithFacebook');

});
Route::controller(AuthController::class)->prefix('/user/auth')->middleware(['localization'])->group(function () {
    Route::post('verify_email', 'verifyEmail');
    Route::post('reset_password', 'resetPassword');

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('/user')->middleware(['localization'])->group(function () {
        Route::get('get_permissions', 'getUserPermission');
    });
    Route::controller(ChallengeController::class)->prefix('/challenge')->middleware(['localization'])->group(function () {
        Route::get('get', 'getChallenges');
    });
});




