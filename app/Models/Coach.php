<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['birth_date' => 'date'];


    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certificatable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
