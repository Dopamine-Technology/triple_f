<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Opportunity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = ['languages' => 'array'];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targeted_type(): belongsTo
    {
        return $this->belongsTo(UserType::class, 'targeted_type', 'id');
    }

    public function position(): belongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): belongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function applicants(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'opportunity_applicants', 'opportunity_id', 'user_id');
    }


}
