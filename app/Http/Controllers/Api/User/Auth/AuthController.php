<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Mail\VerfyMail;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    use AppResponse;

    public function sendVerifyEmail()
    {
        $user = User::query()->where('id', auth()->user()->id)->first();
        Mail::to($user->email)->send(new VerfyMail('Verify Your email', 'follow the steps verify your account'));
        return $this->success(true, 'user received verification email to : ' . $user->email);
    }

    public function verifyEmail()
    {
//        $user = User::query()->whereRaw('md5(id) = "' . $data['user_token'] . '"')->firstOrFail();
        $user = User::query()->where('id', auth()->user()->id)->first();
        $user->email_verified_at = now();
        $user->save();
        return $this->success('user email' . $user->email . ' ' . 'verified successfully');
    }

    public function forgetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|exists:users,email'
        ]);
        $user = User::query()->where('email', $data['email'])->first();
        Mail::to($data['email'])->send(new ForgetPasswordMail('forget your password !', 'follow the steps to reset your password', md5($user->id)));
        return $this->success(true, 'forget password sent to user email : ' . $data['email']);
    }


    public function resetPassword(Request $request)
    {
        $data = $request->validate(
            [
                'user_token' => 'required',
                'password' => 'required|min:8|confirmed',
            ]
        );
        $user = User::query()->whereRaw('md5(id) = "' . $data['user_token'] . '"')->firstOrFail();
        $user->password = Hash::make($data['password']);
        $user->save();
        return $this->success('user ' . $user->email . ' ' . 'password reset successfully');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success(true, 'user logged out');
    }

    public function uniqueEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::query()->where('email', $request->email)->first();
        if (empty($user)) {
            return $this->success(true, 'email is unique !');
        }
        return $this->success(false, 'email already used');
    }

}
