<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           'title'=> $this->getTranslation('title' , LANGUAGE),
           'content'=> $this->getTranslation('content' , LANGUAGE),
           'main_image'=> $this->getMedia('main_image')->first()?->getUrl() ?? '',
           'post_media'=> MediaResource::collection($this->getMedia('post_media')),
           'created_at'=> $this->created_at->format('Y-m-d'),
        ];
    }
}
