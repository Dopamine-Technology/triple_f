<?php

namespace App\Http\Controllers\Api;

use App\Events\NewFollwoerEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Jobs\PusherJob;
use App\Models\Club;
use App\Models\Follow;
use App\Models\User;
use App\Notifications\Users\NewFollower;
use App\Traits\AppResponse;
use App\Traits\NotifyChecker;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    use AppResponse, NotifyChecker;

    public function toggleFollow(User $user)
    {
        $follow = Follow::query()
            ->where('user_id', auth()->user()->id)
            ->where('followed_id', $user->id)
            ->where('user_type_id', $user->user_type_id)
            ->first();
        if (empty($follow)) {
            Follow::query()->create([
                'user_id' => auth()->user()->id,
                'followed_id' => $user->id,
                'user_type_id' => $user->user_type_id,
            ]);
            $user->profile->follower_count = $user->profile->follower_count + 1;
            $user->profile->save();

// notify user
            $this->notificationPermissionsCheck($user, 'new_followers') ? $user->notify(new NewFollower(auth()->user())) : '';
// end notify
            return $this->success(true, 'User Added to your follow list');
        }
        $follow->delete();
        $user->profile->follower_count = $user->profile->follower_count - 1;
        $user->profile->save();
        return $this->success(true, 'User removed to your follow list');

    }

    public function getFollowList()
    {
        return $this->success(UserResource::collection(auth()->user()->followed()->paginate(10)));
    }

    public function getSuggestionsToFollow(Request $request)
    {
        $data = $request->validate([
            "type" => "required",
            "limit" => "sometimes",
            "index" => "sometimes"
        ]);
        $limit = $data['limit'] ?? 10;
        $index = $data['index'] ?? 1;

        $users = User::query()
            ->where('user_type_id', '!=', 0)
            ->where('user_type_id', $data['type'])
            ->whereNotIn('id', auth()->user()->followed()->pluck('user_id')->toArray())
            ->where('id', '!=', auth()->user()->id)
            ->get();
        return $this->success(UserResource::collection($users->sortByDesc(function ($user) {
            return $user->profile->follower_count;
        })->forPage($index, $limit)));
    }


}
