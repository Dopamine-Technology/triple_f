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
    protected $appends = ['reaction_count', 'total_points'];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function reactions(): belongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('points');
    }

//    public function getReactionCountAttribute(): int
//    {
//        return ReactionStatus::query()->where('status_id', $this->id)->count();
//
//    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalPointsAttribute(): int
    {
        return ReactionStatus::query()->where('status_id', $this->id)->sum('points');

    }

}
