<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Club;
use App\Models\Coach;
use App\Models\Scout;
use App\Models\Talent;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use AppResponse;

    public function register(RegisterRequest $request)
    {
        $userData = $request->user;
        $profileData = $request->profile;

        if ($request->google_identifier) {
            return $this->googleRegisterHandler($request);
        }
        if ($request->facebook_identifier) {
            return $this->facebookRegisterHandler($request);
        }


        $newUser = User::query()->create($userData);
        $this->profileCreationHandler($profileData);

        return $this->success([
            'user' => new UserResource($newUser),
            'token' => $newUser->createToken('apptoken')->plainTextToken,
        ], __('User successfully created'));
    }

    public function profileCreationHandler($profileData)
    {
        $type = $profileData['user_type'];
        unset($profileData['user_type']);
        switch ($type) {
            case 1 :
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
            return $this->success([
                'user' => new UserResource($exist_user),
                'token' => $exist_user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in via google'));
        } else {
            $newUser = User::query()->create($userData);
            $newUser->google_identifier = $request->google_identifier;
            $newUser->save();
            $profileData['user_id'] = $newUser->id;
            $this->profileCreationHandler($profileData);
            return $this->success([
                'user' => new UserResource($newUser),
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
            return $this->success([
                'user' => new UserResource($exist_user),
                'token' => $exist_user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in via facebook'));
        } else {
            $newUser = User::query()->create($userData);
            $newUser->google_identifier = $request->google_identifier;
            $newUser->save();
            $profileData['user_id'] = $newUser->id;
            $this->profileCreationHandler($profileData);
            return $this->success([
                'user' => new UserResource($newUser),
                'token' => $newUser->createToken('apptoken')->plainTextToken,
            ], __('User successfully Registered via facebook'));
        }
    }


}
