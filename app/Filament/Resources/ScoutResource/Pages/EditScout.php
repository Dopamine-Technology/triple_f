<?php

namespace App\Filament\Resources\ScoutResource\Pages;

use App\Filament\Resources\ScoutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScout extends EditRecord
{
    protected static string $resource = ScoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
