<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AppController extends Controller
{
    use AppResponse;

    public function getTranslatableStrings()
    {
        return $this->success(TranslationResource::collection(Cache::get('translations', Translation::all())));
    }
}
