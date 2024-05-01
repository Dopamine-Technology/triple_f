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
//        dump(Hash::check($request->password, $user->password));
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password are incorrect.'],
            ]);
        } else {
            if ($user->user_type_id == 3 && !$user->profile->approved_by_admin) {
                throw ValidationException::withMessages([
                    'approved_by_admin' => ['Club need to be approved by admin to proceed'],
                ]);
            }
            if (!$user->profile->approved_by_admin) {
                throw ValidationException::withMessages([
                    'blocked_by_admin' => ['Your Account is currently suspended , please contact with support for more details'],
                ]);
            }
            if (Redis::get('user:profile:' . $user->id)) {
                return $this->success([
                    'source' => 'redis',
                    'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                    'token' => $user->createToken('apptoken')->plainTextToken,
                ], __('User successfully logged in'));
            }else{
                Redis::set('user:profile:' . $user->id , json_encode(new UserResource($user)));
                return $this->success([
                    'source' => 'database',
                    'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                    'token' => $user->createToken('apptoken')->plainTextToken,
                ], __('User successfully logged in'));
            }

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
        if (Redis::get('user:profile:' . $user->id)) {
            return $this->success([
                'source' => 'redis',
                'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }else{
            Redis::set('user:profile:' . $user->id , json_encode(new UserResource($user)));
            return $this->success([
                'source' => 'database',
                'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }


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
        if (Redis::get('user:profile:' . $user->id)) {
            return $this->success([
                'source' => 'redis',
                'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }else{
            Redis::set('user:profile:' . $user->id , json_encode(new UserResource($user)));
            return $this->success([
                'source' => 'database',
                'user' => json_decode(Redis::get('user:profile:' . $user->id)),
                'token' => $user->createToken('apptoken')->plainTextToken,
            ], __('User successfully logged in'));
        }
    }

}
