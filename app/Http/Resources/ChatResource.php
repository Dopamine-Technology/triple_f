<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'image' => $this->image ? 'https://acceptance-test.eu-central-1.linodeobjects.com' . $this->image : '',
            'last_message' => MessageResource::make(
                Message::query()->where(function ($query) {
                    $query->where('message_from', $this->id)
                        ->where('message_to', auth()->user()->id);
                })->orWhere(function ($query) {
                    $query->where('message_from', auth()->user()->id)
                        ->where('message_to', $this->id);
                })->orderBy('id', 'DESC')->first()
            ),
//            'unread_count' => Message::query()->where(function ($query) {
//                $query->where('message_from', $this->id)
//                    ->where('message_to', auth()->user()->id);
//            })->orWhere(function ($query) {
//                $query->where('message_from', auth()->user()->id)
//                    ->where('message_to', $this->id);
//            })->where('is_seen', false)->count()

        ];
    }
}
