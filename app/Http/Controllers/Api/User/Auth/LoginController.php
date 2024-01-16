<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerfyMail;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
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
            if ($user->user_type_id == 3 && !$user->profile->approved_by_admin) {
                throw ValidationException::withMessages([
                    'approved_by_admin' => ['Club need to be approved by admin to proceed'],
                ]);
            }
            return $this->success([
                'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }
    }

    public function loginWithGoogle(Request $request)
    {
        $data = $request->validate(
            [
                'google_identifier' => 'required',
                'email' => 'required|email|exists:users,email'
            ]
        );
        $user = User::query()->where('email', $data['email'])->first();
        $user->google_identifier = $data['google_identifier'];
        $user->save();
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }
        if ($user->user_type_id == 3 && !$user->profile->approved_by_admin) {
            throw ValidationException::withMessages([
                'approved_by_admin' => ['Club need to be approved by admin to proceed'],
            ]);
        }
        return $this->success([
            'user' =>json_decode(Redis::get('user:profile:' . $user->id)),
            'token' => $user->createToken('apptoken')->plainTextToken,
        ], __('User successfully logged in with google'));


    }

    public function loginWithFacebook(Request $request)
    {

//        $title = 'Welcome to the laracoding.com example email';
//        $body = 'Thank you for participating!';
//        Mail::to('ab.basem.j@gmail.com')->send(new VerfyMail($title = 'verify you email', $body = 'test data'));
//TODO: make sure to wrap the mail inside queue
// TODO configure email sending
        $data = $request->validate(
            [
                'facebook_identifier' => 'required',
                'email' => 'required|email|exists:users,email'
            ]
        );
        $user = User::query()->where('email', $data['email'])->first();
        $user->facebook_identifier = $data['facebook_identifier'];
        $user->save();
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }
        if ($user->user_type_id == 3 && !$user->profile->approved_by_admin) {
            throw ValidationException::withMessages([
                'approved_by_admin' => ['Club need to be approved by admin to proceed'],
            ]);
        }
        return $this->success([
            'user' => json_decode(Redis::get('user:profile:' . $user->id)),
            'token' => $user->createToken('apptoken')->plainTextToken,
        ], __('User successfully logged in with facebook'));
    }

}
