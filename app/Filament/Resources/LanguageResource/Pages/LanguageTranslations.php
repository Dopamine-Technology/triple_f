<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use App\Models\Translation;
use Filament\Resources\Pages\Page;

class LanguageTranslations extends Page
{
    protected static string $resource = LanguageResource::class;
    public $record;
    public $language;
    public $translations;

    public function mount()
    {
        $this->language = Language::query()->find($this->record);
        $this->translations = Translation::all();
    }

    protected static string $view = 'filament.resources.language-resource.pages.language-translations';
}
