<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationResource;
use App\Models\ContactUs;
use App\Models\Translation;
use App\Models\User;
use App\Traits\AppResponse;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AppController extends Controller
{
    use AppResponse;

    public function getTranslatableStrings()
    {
        return $this->success(TranslationResource::collection(Cache::get('translations', Translation::all())));
    }

    public function contactUs(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required'
        ]);
        ContactUs::query()->create($data);


        return $this->success([] , 'your message sent successfully');

    }

}
