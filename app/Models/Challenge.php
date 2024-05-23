<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Challenge extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['name', 'description'];
    protected $casts = ['positions' => 'array', 'tips' => 'array', 'is_active' => 'boolean'];

    public function sport(): belongsTo
    {
        return $this->belongsTo(Sport::class);
    }

}
