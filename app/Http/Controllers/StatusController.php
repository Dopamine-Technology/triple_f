<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Http\Resources\StatusResource;
use App\Http\Resources\UserResource;
use App\Models\ReactionStatus;
use App\Models\ReportStatus;
use App\Models\Status;
use App\Models\User;
use App\Models\UserSave;
use App\Services\ReactionService;
use App\Traits\AppResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    use AppResponse;

    public function getStories()
    {
        $followed_users_ids = auth()->user()->followed()->pluck('followed_id')->toArray();
        $followedWithStatuses = User::query()->whereIn('id', $followed_users_ids)
            ->whereHas('statuses')
            ->get()->sortByDesc(function ($user) {
                return $user->statuses->first()->created_at ?? null;
            });
        return $this->success(UserResource::collection($followedWithStatuses));
    }

    public function getUserStories($user_id)
    {
        $statuses = Status::query()->where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
        return $this->success(StatusResource::collection($statuses));

    }


    public function getTimelineStatuses()
    {
        $statuses_user_ids = auth()->user()->followed()->pluck('followed_id')->toArray();
        array_push($statuses_user_ids, auth()->user()->id);
        $trending_this_week = Status::query()->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('total_points', 'DESC')->pluck('id')->toArray();
        $statuses = Status::query()->whereIn('id', $trending_this_week)->whereIn('user_id', $statuses_user_ids)->orderBy('created_at', 'DESC')->get();
        return $this->success(StatusResource::collection($statuses));
    }

    public function getStatusReactions($status_id, Request $request)
    {
        $data = $request->validate([
            'points' => 'sometimes'
        ]);
        $reactions = ReactionStatus::query()->where('status_id', $status_id);
        if (isset($data['points'])) {
            $reactions->where('points', $data['points']);
        }
        $user_ids = $reactions->pluck('user_id')->toArray();
        return $this->success(UserResource::collection(User::query()->whereIn('id', $user_ids)->get()));

    }

    public function getOne(Status $status)
    {
        return $this->success(new StatusResource($status));
    }

    public function createStatus(StatusRequest $request)
    {
        return $this->success(new StatusResource(Status::query()->create($request['status'])));
    }

    public function reactToStatus(Request $request)
    {
        $data = $request->validate([
            'status_id' => 'required',
            'points' => 'required',
        ]);
        ReactionService::handleStatusReaction($data);
        return $this->success(true, 'status reaction updated !');
    }

    public function toggleSave(Status $status)
    {
        $userSavedElement = UserSave::query()->where('user_id', auth()->user()->id)
            ->where('element_id', $status->id)
            ->where('element_type', 'challenge_post')
            ->first();
        if (empty($userSavedElement)) {
            UserSave::query()->create([
                'user_id' => auth()->user()->id,
                'element_id' => $status->id,
                'element_type' => 'challenge_post',
            ]);
            $status->saves = $status->saves + 1;
            $status->save();
            return $this->success(true, 'status added to save !');
        }
        $userSavedElement->delete();
        $status->saves = $status->saves - 1;
        $status->save();
        return $this->success(true, 'status removed from save !');
    }

    public function shareStatus(Status $status)
    {
        $status->shares = $status->shares + 1;
        $status->save();
        return $this->success(true);
    }

    public function reportStatus($status_id, Request $request)
    {
        $data = $request->validate([
            'report' => 'required'
        ]);
        ReportStatus::query()->updateOrCreate(['user_id' => auth()->user()->id, 'status_id' => $status_id,], ['report' => $data['report']]);
        return $this->success(true, 'Status Reported !');

    }


}
