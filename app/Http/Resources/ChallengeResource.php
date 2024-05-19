<?php

namespace App\Http\Resources;

use App\Models\Position;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChallengeResource extends JsonResource
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
            'name' => $this->getTranslation('name', LANGUAGE),
            'description' => $this->getTranslation('description', LANGUAGE),
//            'image' => $this->image ? asset('storage/' . $this->image) : '',
            'image' => $this->image ? 'https://acceptance-test.eu-central-1.linodeobjects.com/' . $this->image : '',
            'sport' => $this->sport ? [
                'id' => $this->sport->id,
                'name' => $this->sport->getTranslation('name', LANGUAGE),
            ] : null,
            'tips' => $this->tips ? collect($this->tips)->map(function ($tip) {
                return $tip[LANGUAGE];
            }) : [],
            'position' => PostionsResource::collection(Position::query()->whereIn('id', $this->positions)->get()),
        ];
    }
}
