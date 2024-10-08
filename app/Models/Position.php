<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Position extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo($this , 'parent_id' , 'id');
    }


}
