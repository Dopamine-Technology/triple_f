<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostionsResource extends JsonResource
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
            'code' => $this->code,
            'image' => $this->image ? 'https://acceptance-test.eu-central-1.linodeobjects.com/positions/' . $this->image : '',
//            'parent' => $this->parent,
        ];
    }


}
