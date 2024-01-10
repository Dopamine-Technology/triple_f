<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Http\Resources\StatusResource;
use App\Models\ReactionStatus;
use App\Models\Status;
use App\Models\UserSave;
use App\Services\ReactionService;
use App\Traits\AppResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    use AppResponse;

    public function getTimelineStatuses()
    {
        $statuses_user_ids = auth()->user()->followed()->pluck('followed_id')->toArray();
        array_push($statuses_user_ids, auth()->user()->id);
        $trending_this_week = Status::query()->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('total_points', 'DESC')->pluck('id')->toArray();
        $statuses = Status::query()->whereIn('id', $trending_this_week)->whereIn('user_id', $statuses_user_ids)->orderBy('created_at', 'DESC')->get();
        return $this->success(StatusResource::collection($statuses));
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


}
