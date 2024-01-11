<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function reactions(): belongsToMany
    {
        return $this->belongsToMany(User::class , 'reaction_statuses' , 'status_id' )->withPivot('points');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
