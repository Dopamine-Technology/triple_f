<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use App\Models\Translation;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LanguageTranslations extends Page
{
    protected static string $resource = LanguageResource::class;
    public $record;
    public $language;
    public $translations;
    public $tagList = LanguageResource::Tags;
    public $inputTag = [];
    public $inputTranslations = [];
    public $key;
    public $value;

    public function mount()
    {
        $this->language = Language::query()->find($this->record);
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
        foreach ($this->translations as $translation) {
            $this->inputTranslations[$translation->id] = $translation->getTranslation('value', $this->language->iso_code);
            $this->inputTag[$translation->id] = $translation->tag;
        }
//        dd( $this->inputTag);

    }

    public function saveNewKey()
    {
        $languages = Language::all();
        $newTranslation = new Translation();

        $newTranslation->key = self::cleanKey($this->key);
        foreach ($languages as $language) {
            $newTranslation->setTranslation('value', $language->iso_code, Str::squish($this->value));
        }
        $newTranslation->save();
        $this->key = '';
        $this->value = '';
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
        $this->inputTranslations = array();
        foreach ($this->translations as $translation) {
            $this->inputTranslations[$translation->id] = $translation->getTranslation('value', $this->language->iso_code);
            $this->inputTag[$translation->id] = $translation->tag;
        }
    }

    public static function cleanKey($key): string
    {
        $clean_key = str_replace('-', ' ', $key);
        $clean_key = Str::snake(Str::squish(preg_replace('/[^A-Za-z0-9\-]/', ' ', $clean_key)));
        return $clean_key;
    }


    public function saveTranslations()
    {

        foreach ($this->inputTranslations as $key => $value) {
            $app_translation = Translation::query()->find($key);
            $translations_list = $app_translation->getTranslations();
            $translations_list['value'][$this->language->iso_code] = $value;
            $app_translation->replaceTranslations('value', $translations_list['value']);
            $app_translation->tag = $this->inputTag[$key];
            $app_translation->save();
        }
        $this->inputTranslations = array();
        $this->inputTag = array();
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
        foreach ($this->translations as $translation) {
            $this->inputTranslations[$translation->id] = $translation->getTranslation('value', $this->language->iso_code);
            $this->inputTag[$translation->id] = $translation->tag;
        }

        Cache::put('translations', $this->translations);
    }


    protected static string $view = 'filament.resources.language-resource.pages.language-translations';
}
