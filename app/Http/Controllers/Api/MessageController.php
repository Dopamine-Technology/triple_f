<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\DeletedChat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\Users\NewMessage;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use AppResponse;

    public function getChats()
    {
        $messages_users = Message::query()->where('message_from', auth()->user()->id)->orWhere('message_to', auth()->user()->id)->pluck('message_from', 'message_to')->toArray();
        $user_ids = array_unique(array_merge(array_keys($messages_users), array_values($messages_users)));
        $deleted_chats = DeletedChat::query()->where('user_id', auth()->user()->id)->pluck('deleted_user_id')->toArray();
        $users = User::query()->whereIn('id', $user_ids)->where('id', '!=', auth()->user()->id)->whereNotIn('id', $deleted_chats)->get();
        return $this->success(ChatResource::collection($users));

    }

    public function getChatMessages($user_id)
    {
        $messages = Message::query()->where(function ($query) use ($user_id) {
            $query->where('message_from', $user_id)
                ->where('message_to', auth()->user()->id);
        })->orWhere(function ($query) use ($user_id) {
            $query->where('message_from', auth()->user()->id)
                ->where('message_to', $user_id);
        })->orderBy('id', 'DESC')->get();

        return $this->success(MessageResource::collection($messages));

    }


    public function sendMessage(Request $request)
    {
        $data = $request->all();
        Message::query()->create(['message_to' => $data['message_to'], 'message_from' => auth()->user()->id, 'message' => $data['message']]);
        $user = User::query()->find($data['message_to']);
        $user->notify(new NewMessage(auth()->user()));
        DeletedChat::query()->where('user_id', $data['message_to'])->where('deleted_user_id', auth()->user()->id)->delete();
        return $this->success(true);
    }

    public function updateSeenMessage($user_id)
    {


        Message::query()->where(function ($query) use ($user_id) {
            $query->where('message_from', $user_id)
                ->where('message_to', auth()->user()->id);
        })->orWhere(function ($query) use ($user_id) {
            $query->where('message_from', auth()->user()->id)
                ->where('message_to', $user_id);
        })->update(['is_seen' => true]);
        return $this->success(true);
    }

    public function deleteChat($user_id)
    {
        DeletedChat::query()->updateOrCreate(['user_id' => auth()->user()->id, 'deleted_user_id' => $user_id], ['user_id' => auth()->user()->id, 'deleted_user_id' => $user_id]);
        return $this->success(true);

    }


}
