<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$data['password']) {
            unset($data['password']);
        }
        $data['is_admin'] = true;
        $role = Role::query()->find($data['role'])->name;
        unset($data['role']);
        $record->update($data);
        $record->assignRole($role);
        return $record;
    }


}
