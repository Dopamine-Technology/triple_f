<?php

namespace App\Filament\Resources\TalentsResource\Pages;

use App\Filament\Resources\TalentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalents extends EditRecord
{
    protected static string $resource = TalentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
