<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\CitiesResource;
use App\Http\Resources\CountriesResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\PostionsResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SportResource;
use App\Http\Resources\TranslationResource;
use App\Http\Resources\UsertypeResource;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Language;
use App\Models\Post;
use App\Models\Sport;
use App\Models\Translation;
use App\Models\UserType;
use App\Traits\AppResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class AppController extends Controller
{
    use AppResponse;

    public function getSiteLocaleLanguages()
    {
        $languages = Language::query()->where('is_site_locale', true)->get();
        return $this->success(LanguageResource::collection($languages));
    }

    public function getLanguages()
    {
        return $this->success(LanguageResource::collection(Language::all()));
    }

    public function getTranslatableStrings()
    {
        $translations = request()->tag ? Translation::query()->where('tag', request()->tag)->get() : Translation::all();
        return $this->success(TranslationResource::collection($translations));
    }

    public function contactUs(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|max:255', 'message' => 'required']);
        ContactUs::query()->create($data);
        return $this->success([], 'your message sent successfully');
    }

    public function getLatestPosts(Request $request)
    {
        $offset = $request->page ?? 0;
        $limit = $request->limit ?? 10;
        $posts = Post::query()->skip($offset * $limit)->limit($limit)->orderBy('created_at', 'DESC')->get();
        return $this->success(PostResource::collection($posts));
    }

    public function getUserTypes()
    {
        return $this->success(UsertypeResource::collection(UserType::all()));
    }

    public function getSports()
    {
        return $this->success(SportResource::collection(Sport::all()));
    }

    public function getCountries()
    {
        return $this->success(CountriesResource::collection(Country::query()->get()));
    }

    public function getCities($country_id = 0)
    {
        $cities = $country_id ? City::query()->where('country_id', $country_id)->get() : City::query()->get();
        return $this->success(CitiesResource::collection($cities));
    }

    public function getSportPositions(Sport $sport, Request $request)
    {
        $positions = $sport->positions();
        if ($request->name) {
            $positions->where('name->' . LANGUAGE, 'LIKE', '%' . $request->name . '%');
        }
        if (!$request->parent_id) {
            $positions = $positions->where('parent_id', 0);
        } else {
            $positions = $positions->where('parent_id', $request->parent_id);
        }
        return $this->success(PostionsResource::collection($positions->get()));
    }
}
