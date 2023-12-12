<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\User\Auth\RegisterController;
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
    Route::post('contact_us', 'contactUs');
    Route::post('latest_posts', 'getLatestPosts');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(RegisterController::class)->prefix('/user/auth')->middleware(['localization'])->group(function () {
    Route::post('register', 'register');

});
