<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
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
}
