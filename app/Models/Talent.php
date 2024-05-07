<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['birth_date' => 'date', 'positions' => 'array'];
    protected $with = ['sport', 'parent_position'];
    protected $table = 'talents';
    protected $appends = ['age'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function parent_position()
    {
        return $this->belongsTo(Position::class, 'parent_position_id');
    }

//    public function position()
//    {
//        return $this->belongsTo(Position::class, 'position_id');
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }


}
