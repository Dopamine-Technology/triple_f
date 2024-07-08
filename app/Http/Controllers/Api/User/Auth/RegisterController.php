<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerfyMail;
use App\Models\Club;
use App\Models\Coach;
use App\Models\Scout;
use App\Models\Talent;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class RegisterController extends Controller
{
    use AppResponse;

    public function register(RegisterRequest $request)
    {

//        Mail::to('abdullah.basem.j@gmail.com')->send(new VerfyMail('welcome' , 'first time sending emails !'));
        $userData = $request->user;
        $profileData = $request->profile;

        if ($request->google_identifier) {
            return $this->googleRegisterHandler($request);
        }
        if ($request->facebook_identifier) {
            return $this->facebookRegisterHandler($request);
        }
        $newUser = User::query()->create($userData);
//        Cache::store('redis')->put('user_' . $newUser->id, $newUser, now()->addHours(4)); // 4 Hours

        $profileData['user_id'] = $newUser->id;
        $this->profileCreationHandler($profileData);
        return $this->success([
            'data_source' => 'redis',
            'token' => $newUser->createToken('apptoken')->plainTextToken,
            'user' => new UserResource( $newUser),
        ], __('User successfully created'));
    }

    public function profileCreationHandler($profileData)
    {
        $type = $profileData['user_type'];
        unset($profileData['user_type']);

        switch ($type) {
            case 1 :
//                dd($profileData);
                return Talent::query()->create($profileData);
            case 2 :
                return Coach::query()->create($profileData);
            case 3 :
                return Club::query()->create($profileData);
            case 4 :
                return Scout::query()->create($profileData);
        }

    }

    public function googleRegisterHandler($request)
    {
        $userData = $request->user;
        $profileData = $request->profile;
        $exist_user = User::query()->where('google_identifier', $request->google_identifier)->orWhere('email', $request->email)->first();
        if (!empty($exist_user)) {
            $exist_user->google_identifier = $request->google_identifier;
            $exist_user->email_verified_at = now();
            $exist_user->save();

//            Cache::store('redis')->put('user_' . $exist_user->id, $exist_user, now()->addHours(4)); // 4 Hours
            return $this->success([
                'data_source'=>'redis',
                'user' => UserResource::make($exist_user),
                'token' => $exist_user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in via google'));
        } else {
            $newUser = User::query()->create($userData);
            $newUser->google_identifier = $request->google_identifier;
            $newUser->save();
            $profileData['user_id'] = $newUser->id;

            $this->profileCreationHandler($profileData);
//            Cache::store('redis')->put('user_' . $newUser->id, $newUser, now()->addHours(4)); // 4 Hours
            return $this->success([
                'data_source' => 'redis',
                'user' => UserResource::make($newUser),
                'token' => $newUser->createToken('apptoken')->plainTextToken,
            ], __('User successfully Registered via google'));
        }
    }

    public function facebookRegisterHandler($request)
    {
        $userData = $request->user;
        $profileData = $request->profile;
        $exist_user = User::query()->where('facebook_identifier', $request->facebook_identifier)->orWhere('email', $request->email)->first();
        if (!empty($exist_user)) {
            $exist_user->facebook_identifier = $request->facebook_identifier;
            $exist_user->email_verified_at = now();
            $exist_user->save();
//            Cache::store('redis')->put('user_' . $exist_user->id, $exist_user, now()->addHours(4)); // 4 Hours
            return $this->success([
                'data_source'=>'redis',
                'user' => UserResource::make($exist_user),
                'token' => $exist_user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in via facebook'));
        } else {
            $newUser = User::query()->create($userData);
            $newUser->google_identifier = $request->google_identifier;
            $newUser->save();
            $profileData['user_id'] = $newUser->id;
            $this->profileCreationHandler($profileData);
//            Cache::store('redis')->put('user_' . $newUser->id, $newUser, now()->addHours(4)); // 4 Hours
            return $this->success([
                'data_source'=>'redis',
                'user' => UserResource::make($newUser),
                'token' => $newUser->createToken('apptoken')->plainTextToken,
            ], __('User successfully Registered via facebook'));
        }
    }
}
