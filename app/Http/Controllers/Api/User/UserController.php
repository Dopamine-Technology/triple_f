<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileEditRequest;
use App\Http\Resources\UserResource;
use App\Models\BlockedUsers;
use App\Models\Club;
use App\Models\Coach;
use App\Models\Follow;
use App\Models\Scout;
use App\Models\Talent;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

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
        return $this->success(new UserResource(User::query()->find(auth()->user()->id)), 'database');
//        if (Redis::get('user:profile:' . auth()->user()->id)) {
//            return $this->success(json_decode(Redis::get('user:profile:' . auth()->user()->id)), 'redis');
//        } else {
//            $user = User::query()->find(auth()->user()->id);
//            Redis::set('user:profile:' . $user->id, json_encode(new UserResource($user)));
//            return $this->success(json_decode(Redis::get('user:profile:' . auth()->user()->id)), 'database');
//        }
    }

    public function getUserByID($user_id)
    {
        $user = User::query()->find($user_id);
        return $this->success(new UserResource($user));
    }

    public function getFollowingList($user_id)
    {
        $following_ids = Follow::query()->where('user_id', $user_id)->pluck('followed_id')->toArray();
        return $this->success(UserResource::collection(User::query()->whereIn('id', $following_ids)->get()));
    }

    public function getFollowersList($user_id)
    {
        $followers_ids = Follow::query()->where('followed_id', $user_id)->pluck('user_id')->toArray();
        return $this->success(UserResource::collection(User::query()->whereIn('id', $followers_ids)->get()));
    }

    public function updateProfile(ProfileEditRequest $request)
    {
        User::query()->where('id', auth()->user()->id)->update($request->user);
        $profileType = 'App\Models\\' . ucfirst(auth()->user()->profile_type->name);
        $profileType::query()->where('user_id', auth()->user()->id)->update($request->profile);
//        Redis::set('user:profile:' . auth()->user()->id, json_encode(new UserResource(auth()->user())));
        return $this->success(true, 'user ' . ucfirst(auth()->user()->profile_type->name) . ' profile successfully updated');
    }

    public function editPassword(Request $request)
    {
        $data = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);
        $user = User::query()->where('id', auth()->user()->id)->first();
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['The provided credentials are incorrect.'],
            ]);
        } else {
            $user->password = Hash::make($data['new_password']);
            $user->save();
        }
        return $this->success(true, 'password updated successfully !');
    }

    public function notificationSettings(Request $request)
    {
        $data = $request->validate(['notifications' => 'required']);
        $user = auth()->user();
        auth()->user()->notification_settings = $data['notifications'];
        auth()->user()->save();
        auth()->guard('web')->login($user);
//        dd(auth()->user());
        return $this->success(true, 'notifications settings successfully updated');
    }

    public function blockUser($user_id)
    {
        $auth_id = auth()->id();
        Follow::query()->where(function (Builder $query) use ($auth_id, $user_id) {
            $query->where('user_id', $auth_id)->where('followed_id', $user_id);
        })->orWhere(function (Builder $query) use ($auth_id, $user_id) {
            $query->where('user_id', $user_id)->where('followed_id', $auth_id);
        })->delete();
        BlockedUsers::query()->updateOrCreate(['user_id' => $auth_id, 'blocked_id' => $user_id]);
        BlockedUsers::query()->updateOrCreate(['user_id' => $user_id, 'blocked_id' => $auth_id]);
        return $this->success(true, 'user blocked successfully');

    }


}
