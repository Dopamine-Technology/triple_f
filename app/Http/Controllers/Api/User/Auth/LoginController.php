<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AppResponse;

    public function loginWithEmail(LoginRequest $request)
    {
        $user = User::query()->where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        } else {
            return $this->success([
                'user' => new UserResource($user),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }
    }

    public function loginWithGoogle(Request $request)
    {
        $data = $request->validate(
            [
                'google_identifier' => 'required|exists:users,google_identifier'
            ]
        );
        $user = User::query()->where('google_identifier', $data['google_identifier'])->first();
        if(!$user->email_verified_at){
            $user->email_verified_at = now();
            $user->save();
        }
        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('apptoken')->plainTextToken,
        ], __('User successfully logged in with google'));


    }

    public function loginWithFacebook(Request $request)
    {
        $data = $request->validate(
            [
                'facebook_identifier' => 'required|exists:users,facebook_identifier'
            ]
        );
        $user = User::query()->where('facebook_identifier', $data['facebook_identifier'])->first();

        if(!$user->email_verified_at){
            $user->email_verified_at = now();
            $user->save();
        }
        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('apptoken')->plainTextToken,
        ], __('User successfully logged in with facebook'));
    }

}
