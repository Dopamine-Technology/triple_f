<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\AppResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use AppResponse;

    public function getAll()
    {
        $notifications = auth()->user()->notifications()->get();
        auth()->user()->notifications->markAsRead();
        return $this->success(NotificationResource::collection($notifications));
    }

    public function deleteAll()
    {
        auth()->user()->notifications()->delete();
        return $this->success(true , 'notifications deleted successfully');
    }


}
