<?php

namespace App\Services;

use App\Models\ReactionStatus;
use App\Models\Status;

class ReactionService
{
    public static function handleStatusReaction($data): void
    {
        $status = Status::find($data['status_id']);
        $reaction = ReactionStatus::query()->where('user_id', auth()->user()->id)->where('status_id', $data['status_id'])->first();
        $old_point = $reaction->points ?? 0;
        $reaction?->delete();
        if ($old_point) {
            switch ($old_point) {
                case 1:
                    $status->bronze_reacts = $status->bronze_reacts - 1;
                    break;
                case 2:
                    $status->silver_reacts = $status->silver_reacts - 1;
                    break;
                case 3:
                    $status->gold_reacts = $status->gold_reacts - 1;
            }
            $status->total_points = $status->total_points - $old_point;
            $status->save();
        }
        ReactionStatus::query()->create(['user_id' => auth()->user()->id, 'status_id' => $data['status_id'], 'points' => $data['points']]);
        switch ($data['points']) {
            case 1:
                $status->bronze_reacts = $status->bronze_reacts + 1;
                break;
            case 2:
                $status->silver_reacts = $status->silver_reacts + 1;
                break;
            case 3:
                $status->gold_reacts = $status->gold_reacts + 1;
        }
        $status->total_points = $status->total_points + $data['points'];
        $status->save();

    }
}
