<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['birth_date' => 'date'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
