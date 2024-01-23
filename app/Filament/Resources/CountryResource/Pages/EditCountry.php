<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Models\Country;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $parent = parent::handleRecordUpdate($record, $data); // TODO: Change the autogenerated stub
        $this->setCache();
        return $parent;
    }
    public function setCache(): void
    {
        $country_list = array();
        $countries = Country::all();
        foreach ($countries as $country) {
            $country_list['en'][] = [
                'id' => $country->id,
                'iso' => $country->iso_code,
                'name' => $country->getTranslation('name', 'en'),
            ];
            $country_list['ar'][] = [
                'id' => $country->id,
                'iso' => $country->iso_code,
                'name' => $country->getTranslation('name', 'ar'),
            ];

            Redis::set('countries_en', json_encode($country_list['en']));
            Redis::set('countries_ar', json_encode($country_list['ar']));

        }

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
