<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use App\Models\Translation;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageTranslations extends Page
{
    protected static string $resource = LanguageResource::class;
    public $record;
    public $language;
    public $translations;

    public $inputTranslations = [];

    public $key;
    public $value;

    public function mount()
    {

        $this->language = Language::query()->find($this->record);
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
        foreach ($this->translations as $translation) {
            $this->inputTranslations[$translation->id] = $translation->getTranslation('value', $this->language->iso_code);
        }
    }

    public function saveNewKey()
    {
        $languages = Language::all();
        $newTranslation = new Translation();
        $newTranslation->key = $this->key;
        foreach ($languages as $language) {
            $newTranslation->setTranslation('value', $language->iso_code, $this->value);
        }
        $newTranslation->save();
        $this->key = '';
        $this->value = '';
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
    }

    public function saveTranslations()
    {
        foreach ($this->inputTranslations as $key => $value) {
            $app_translation = Translation::query()->find($key);
            $translations_list = $app_translation->getTranslations();
            $translations_list['value'][$this->language->iso_code] = $value;
            $app_translation->replaceTranslations('value', $translations_list['value']);
            $app_translation->save();
        }
        $this->inputTranslations = array();
        $this->translations = Translation::query()->orderBy('id', 'DESC')->get();
        foreach ($this->translations as $translation) {
            $this->inputTranslations[$translation->id] = $translation->getTranslation('value', $this->language->iso_code);
        }
        $this->inputTranslations = Translation::query()->pluck('value', 'id')->toArray();
        Cache::put('translations', $this->translations);
    }


    protected static string $view = 'filament.resources.language-resource.pages.language-translations';
}
