<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $record->admin_id = auth()->user()->id;
        return $record->setTranslation('title', 'ar', $data['title']['ar'])
            ->setTranslation('title', 'en', $data['title']['en'])
            ->setTranslation('content', 'ar', $data['content']['ar'])
            ->setTranslation('content', 'en', $data['content']['en'])->save();

    }

}
