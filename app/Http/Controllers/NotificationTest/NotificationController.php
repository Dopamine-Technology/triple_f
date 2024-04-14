<?php

namespace App\Http\Controllers\NotificationTest;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        return view('test.notifications');
    }


    public function submitLogin(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        auth()->attempt($data);
        return redirect()->route('notifications');

    }
}
