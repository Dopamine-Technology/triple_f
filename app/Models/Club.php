<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
// this model has an observer : ClubObserver
    protected $guarded = [];
    protected $with = ['country'];
    protected $casts = ['approved_by_admin' => 'boolean'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certificatable');
    }

}
