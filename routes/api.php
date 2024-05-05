<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OpportunityController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\User\Auth\AuthController;
use App\Http\Controllers\Api\User\Auth\LoginController;
use App\Http\Controllers\Api\User\Auth\RegisterController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\UserProfileController;
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
    Route::get('get_locales', 'getSiteLocaleLanguages');
    Route::get('languages', 'getLanguages');
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
    Route::post('unique_email', 'uniqueEmail');

});
Route::controller(AuthController::class)->middleware('auth:sanctum')->group(function () {
    Route::delete('auth/logout', 'logout');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('/user')->middleware(['localization'])->group(function () {
        Route::put('edit', 'updateProfile');
        Route::post('notification_settings', 'notificationSettings');
        Route::post('edit_password', 'editPassword');
        Route::get('get_permissions', 'getUserPermission');
        Route::get('profile', 'getUserProfile');
        Route::get('get_profile/{user_id}', 'getUserByID');
        Route::get('following/{user_id}', 'getFollowingList');
        Route::get('followers/{user_id}', 'getFollowersList');
    });
    Route::controller(ChallengeController::class)->prefix('/challenge')->middleware(['localization'])->group(function () {
        Route::get('get', 'getChallenges');
    });
    Route::controller(StatusController::class)->prefix('/status')->middleware(['localization'])->group(function () {
        Route::get('timeline', 'getTimelineStatuses');
        Route::delete('delete/{status_id}', 'deleteStatus');
        Route::post('create', 'createStatus');
        Route::get('get/{status}', 'getOne');
        Route::post('react', 'reactToStatus');
        Route::post('get_reactions/{status}', 'getStatusReactions');
        Route::get('toggle_save/{status}', 'toggleSave');
        Route::get('get_saved', 'getSavedStatuses');
        Route::put('share_status/{status}', 'shareStatus');
        Route::post('report/{status_id}', 'reportStatus');
        Route::get('stories', 'getStories');
        Route::get('user_stories/{user_id}', 'getUserStories');
        Route::get('user_statuses/{user_id}', 'getUserStatuses');
        Route::get('block_status/{status_id}', 'blockStatus');
        Route::post('update_seen_stories', 'updateSeenStories');
    });
    Route::controller(FollowController::class)->prefix('/follow')->middleware(['localization'])->group(function () {
        Route::get('toggle/{user}', 'toggleFollow');
        Route::get('all', 'getFollowList');
        Route::post('get_recommendations', 'getSuggestionsToFollow');
    });
    Route::controller(OpportunityController::class)->prefix('/opportunities')->middleware(['localization'])->group(function () {
        Route::get('find', 'findOpportunities');
        Route::get('apply/{opportunity_id}', 'apply');
        Route::post('create', 'create');
        Route::get('toggle_status/{opportunity}', 'toggleStatus');
        Route::post('user_opportunities', 'getUserOpportunities');
        Route::get('applicants/{opportunity}', 'getApplicants');
        Route::get('user_published_opportunities/{user_id}', 'getUserPublishedOpportunities');
    });

    Route::controller(NotificationController::class)->prefix('/notifications')->middleware(['localization'])->group(function () {
        Route::get('all', 'getAll');
        Route::delete('clear', 'deleteAll');
    });
    Route::controller(UserProfileController::class)->prefix('/profiles')->middleware(['localization'])->group(function () {
        Route::post('talents', 'findTalentsProfiles');
        Route::post('coaches', 'findCoachesProfiles');
        Route::post('clubs', 'findClubsProfiles');
        Route::post('scout', 'findScoutsProfiles');
        Route::post('create_certificate', 'createCertificate');
        Route::get('get_certificate/{user}', 'getProfileCertificate');
        Route::post('edit_certificate/{certificate_id}', 'editCertificate');
        Route::delete('delete_certificate/{certificate}', 'deleteCertificate');
        Route::post('create_licence', 'createLicence');
        Route::get('get_licences/{user}', 'getUserLicence');
        Route::post('edit_licence/{licence_id}', 'editLicence');
        Route::delete('delete_licence/{licence_id}', 'deleteLicence');
    });
    Route::controller(MessageController::class)->prefix('/chat')->middleware(['localization'])->group(function () {
        Route::get('get_chats', 'getChats');
        Route::get('get_chat_messages/{user_id}', 'getChatMessages');
        Route::post('send_message', 'sendMessage');

    });
});




