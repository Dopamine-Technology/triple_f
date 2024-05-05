<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'message_from' => $this->message_from,
            'message_to' => $this->message_to,
            'message' => $this->message,
            'file_path' => $this->file_path ?? '',
            'is_seen' => $this->is_seen,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
