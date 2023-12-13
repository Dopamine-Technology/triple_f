<?php

namespace App\Filament\Resources\UserTypesResource\Pages;

use App\Filament\Resources\UserTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserTypes extends ListRecords
{
    protected static string $resource = UserTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
