<?php

namespace App\Http\Resources;

use App\Models\Follow;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStoriesResource extends JsonResource
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
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'name' => $this->first_name ?? '' . ' ' . $this->last_name ?? '',
            'image' => $this->image ? asset('storage/' . $this->image) : '',
            'social_image' => $this->social_image ?? '',
            'user_name' => $this->user_name ?? '',
            'stories' => statusResource::collection(Status::query()->where('user_id', $this->id)->orderBy('created_at', 'DESC')->get())
        ];
    }
}
