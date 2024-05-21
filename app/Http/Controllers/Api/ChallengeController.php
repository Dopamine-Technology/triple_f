<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\StatusResource;
use App\Models\Challenge;
use App\Models\Status;
use App\Traits\AppResponse;

class ChallengeController extends Controller
{
    use  AppResponse;

    public function getChallenges()
    {
        $challenges = Challenge::query()->where(function ($query) {
            $query->where('sport_id', auth()->user()->profile->sport_id);
            $query->where('positions', 'like', "%" . auth()->user()->profile->position_id . "%");
        })->orWhere(function ($query) {
            $query->orWhere('sport_id', 0);
            $query->orWhere('positions', 'like', "[]");
        })->get();
        return $this->success(ChallengeResource::collection($challenges));
    }

    public function getRecommendedChallenges()
    {
        $followed_users = auth()->user()->followed()->pluck('followed_id')->toArray();
        $followed_users[] = auth()->id();
        $statuses = Status::query()->whereNotIn('user_id', $followed_users)->orderBy('created_at', 'DESC')->limit(5)->get();
        return $this->success(StatusResource::collection($statuses));
    }
}
