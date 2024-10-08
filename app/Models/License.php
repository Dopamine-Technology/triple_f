<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['issued_date' => 'date', 'expiration_date' => 'date'];
}
