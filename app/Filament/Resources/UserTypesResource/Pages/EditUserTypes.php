<?php

namespace App\Filament\Resources\UserTypesResource\Pages;

use App\Filament\Resources\UserTypesResource;
use App\Models\UserType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUserTypes extends EditRecord
{
    protected static string $resource = UserTypesResource::class;

//    protected function handleRecordUpdate(Model $record, array $data): Model
//    {
//        $permission_list = UserType::$permisions_options;
//        $bool_permission = array();
//        foreach ($permission_list as $key => $value) {
//            $bool_permission[$key] = false;
//        }
//
//        if (isset($data['selected_permission'])) {
//            foreach ($data['selected_permission'] as $key)
//                $bool_permission[$key] = true;
//        }
//        $record->permissions = $bool_permission;
//        $record->save();
//        return $record;
//    }

    protected function getHeaderActions(): array
    {


        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
