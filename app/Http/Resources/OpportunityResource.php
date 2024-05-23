<?php

namespace App\Http\Resources;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityResource extends JsonResource
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
            'applicants_count'=>$this->applicants()->count(),
            'title' => $this->title,
            'requirements' => $this->requirements,
            'additional_info' => $this->additional_info,
            'status' => $this->status,
            'is_published' => $this->user_id == auth()->user()->id ? 'published' : 'applied',
            'from_age' => $this->from_age,
            'to_age' => $this->to_age,
            'from_height' => $this->from_height,
            'to_height' => $this->to_height,
            'from_weight' => $this->from_weight,
            'to_weight' => $this->to_weight,
            'gender' => $this->gender,
            'foot' => $this->foot,
            'position' => new PostionsResource($this->position),
            'targeted_type' => $this->targeted_type,
            'user' => new UserResource($this->user),
            'languages' => !empty($this->languages) ? LanguageResource::collection(Language::query()->whereIn('id', $this->languages)->get()) : [],
            'country' => [
                'id' => $this->country->id,
                'name' => $this->country->getTranslation('name', LANGUAGE),
            ],
            'city' => $this->city->getTranslation('name', LANGUAGE),
            'created_at' => $this->created_at->diffInHours() >= 24 ? $this->created_at->format('Y-m-d') : $this->created_at->diffForHumans()
        ];
    }
}
