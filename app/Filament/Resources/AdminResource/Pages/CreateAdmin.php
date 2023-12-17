<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['is_admin'] = true;

        $role = Role::query()->find(1)->name;

        unset($data['role']);
        $admin = User::query()->create($data);
        $admin->assignRole($role);
        return $admin;
    }


}
