<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Sport extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    public function positions(): hasMany
    {
        return $this->hasMany(Position::class);
    }

}
