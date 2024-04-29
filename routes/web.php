<?php

use App\Http\Controllers\NotificationTest\NotificationController;
use App\Http\Controllers\NotificationTest\TransllationController;
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

Route::get('/', function () {
    return view('coming_soon');
});
Route::controller(NotificationController::class)->middleware(['auth'])->group(function () {
    Route::get('notifications', 'getNotifications')->name('notifications');
});


Route::get('login', function () {
    return view('test.login');
})->name('login');

Route::controller(NotificationController::class)->group(function () {
    Route::post('submit_login', 'submitLogin')->name('submit_login');
});

Route::controller(TransllationController::class)->prefix('translation')->group(function () {
    Route::get('form', 'getForm');
    Route::post('post', 'submitForm')->name('translation.submit');
});

//Route::get('/notifications', function () {
//    return '<h1>notifications</h1>';
//});
