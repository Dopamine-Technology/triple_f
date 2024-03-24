<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    use AppResponse;

    public function verifyEmail(Request $request)
    {
        $data = $request->validate(
            [
                'user_token' => 'required',
            ]
        );
        $user = User::query()->whereRaw('md5(id) = "' . $data['user_token'] . '"')->firstOrFail();
        $user->email_verified_at = now();
        $user->save();
        return $this->success('user email' . $user->email . ' ' . 'verified successfully');
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
            'email' => 'required|unique:users,email|email'
        ]);
        return $this->success(true, 'email is unique !');
    }

}
