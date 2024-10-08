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
    protected $appends = ['is_reacted', 'Is_saved'];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function reactions(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'reaction_statuses', 'status_id')->withPivot('points');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'report_statuses', 'status_id')->withPivot(['report', 'is_reviewed']);
    }

    public function getIsReactedAttribute()
    {
        $reaction = ReactionStatus::query()->where('status_id', $this->id)->where('user_id', auth()->user()->id)->first();
        return $reaction?->points ?? 0;
    }

    public function getIsSavedAttribute(): bool
    {
        $saved = UserSave::query()->where('user_id', auth()->user()->id)->where('element_type', 'challenge_post')->where('element_id', $this->id)->first();
        return !empty($saved);
    }


}
