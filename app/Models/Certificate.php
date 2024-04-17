<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];
   protected $casts = ['issued_date'=>'date'];
    public function certificatable()
    {
        return $this->morphTo();
    }
}
