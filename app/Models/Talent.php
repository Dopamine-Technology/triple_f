<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['birth_date' => 'date'];
    protected $with = ['sport', 'parent_position', 'position'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function parent_position()
    {
        return $this->belongsTo(Position::class, 'parent_position_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

}
