<?php

namespace App\Http\Resources;

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
            'video' => $this->video ? asset('storage/' . $this->video) : '',
            'image' => $this->image ? asset('storage/' . $this->image) : '',
            'shares' => $this->shares,
            'saves' => $this->saves,
            'reaction_count' => $this->reaction_count,
            'challenge' => [
                'id' => $this->challenge->id,
                'name' => $this->challenge->getTranslation('name', LANGUAGE),
            ],
            'total_points' => $this->total_points,
            'created_at' => Carbon::create($this->created_at)->diffForHumans(),
        ];
    }
}
