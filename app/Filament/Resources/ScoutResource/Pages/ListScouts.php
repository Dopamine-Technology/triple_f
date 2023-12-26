<?php

namespace App\Filament\Resources\ScoutResource\Pages;

use App\Filament\Resources\ScoutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScouts extends ListRecords
{
    protected static string $resource = ScoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
