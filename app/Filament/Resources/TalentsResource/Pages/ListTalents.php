<?php

namespace App\Filament\Resources\TalentsResource\Pages;

use App\Filament\Resources\TalentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalents extends ListRecords
{
    protected static string $resource = TalentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
