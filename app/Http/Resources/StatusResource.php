<?php

namespace App\Http\Resources;

use App\Models\SeenStorie;
use App\Models\UserSave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'description' => $this->description ?? '',
//            'video' => $this->video ? asset('storage/' . $this->video) : '',
//            'image' => $this->image ? asset('storage/' . $this->image) : '',
            'video' => $this->video ? 'https://acceptance-test.eu-central-1.linodeobjects.com/' . $this->video : '',
            'image' => $this->image ? 'https://acceptance-test.eu-central-1.linodeobjects.com/'. $this->image : '',
            'shares' => $this->shares,
            'saves' => $this->saves,
            'reaction_count' => $this->gold_reacts + $this->silver_reacts + $this->bronze_reacts,
            'gold_reacts_count' => $this->gold_reacts,
            'silver_reacts_count' => $this->silver_reacts,
            'bronze_reacts_count' => $this->bronze_reacts,
            'challenge' => [
                'id' => $this->challenge?->id ?? '',
                'name' => $this->challenge?->getTranslation('name', LANGUAGE),
            ],
            'user' => new UserResource($this->user),
            'is_seen' => !empty(SeenStorie::query()->where('user_id', auth()->user()->id)->where('seen_user_id', $this->user->id)->first()),
            'is_reacted' => $this->is_reacted,
            'is_saved' => $this->is_saved,
            'total_points' => $this->total_points,
            'created_at' => Carbon::create($this->created_at)->diffForHumans(),
        ];
    }
}
