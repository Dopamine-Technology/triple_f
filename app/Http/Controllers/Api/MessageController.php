<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Message;
use App\Models\User;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use AppResponse;

    public function getChats()
    {
        $messages_users = Message::query()->where('message_from', auth()->user()->id)->orWhere('message_to', auth()->user()->id)->pluck('message_from', 'message_to')->toArray();
        $user_ids = array_unique(array_merge(array_keys($messages_users), array_values($messages_users)));
        $users = User::query()->whereIn('id', $user_ids)->where('id', '!=', auth()->user()->id)->get();

        return $this->success(ChatResource::collection($users));

    }

    public function getChatMessages()
    {

    }


    public function sendMessage(Request $request)
    {

    }

    public function updateMessageSeen(Request $request)
    {

    }


}
