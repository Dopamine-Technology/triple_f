<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $newsPost = new Post();
        $newsPost->admin_id = auth()->user()->id;
        $newsPost
//            ->setTranslation('title', 'ar', $data['title']['ar'])
            ->setTranslation('title', 'en', $data['title']['en'])
//            ->setTranslation('content', 'ar', $data['content']['ar'])
            ->setTranslation('content', 'en', $data['content']['en'])->save();
        return $newsPost;


    }
}
