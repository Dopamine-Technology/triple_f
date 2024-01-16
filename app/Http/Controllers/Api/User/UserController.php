<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    use AppResponse;

    public function getUserPermission()
    {
        $permissions = auth()->user()->profile_type->permissions;
        return $this->success($permissions);
    }

    public function getUserProfile()
    {
        if (Redis::get('user:profile:' . auth()->user()->id)) {
            return $this->success(json_decode(Redis::get('user:profile:' . auth()->user()->id)), 'redis');
        } else {
            $user = User::query()->find(auth()->user()->id);
            Redis::set('user:profile:' . $user->id, json_encode(new UserResource($user)));
            return $this->success(json_decode(Redis::get('user:profile:' . auth()->user()->id)), 'database');
        }
    }


}
