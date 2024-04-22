<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia, FilamentUser
{
//observedBy:UserObserver
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRoles;


    protected $with = ['profile_type'];
    protected $appends = ['profile', 'Progress'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_blocked' => 'boolean',
        'notification_settings' => 'array',
    ];

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.' . $this->id;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function profile_type(): belongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function talent()
    {
        return $this->hasOne(Talent::class);
    }

    public function scout(): hasOne
    {
        return $this->hasOne(Scout::class, 'user_id', 'id');
    }

    public function club()
    {
        return $this->hasOne(Club::class, 'user_id', 'id');
    }

    public function coach(): hasOne
    {
        return $this->hasOne(Coach::class, 'user_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function getProfileAttribute()
    {

        if ($this->user_type_id == 1) {
            return $this->talent;
        }
        if ($this->user_type_id == 2) {

            return $this->coach;
        }
        if ($this->user_type_id == 3) {
            return $this->club;
        }
        if ($this->user_type_id == 4) {
            return $this->scout;
        }
        return null;
    }

    public function followed()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'followed_id');
    }

    public function getProgressAttribute()
    {
        $attributes = ["verify_email" => false, "personal_photo" => false, "cover_photo" => false, "personal_info" => true];
        $progress = 1;
        if ($this->image != 'profile_avatar.svg') {
            $attributes['personal_photo'] = true;
            $progress++;
        }
        if ($this->banner_image != null && $this->banner_image != 'profile_cover.jpg') {
            $attributes['cover_photo'] = true;
            $progress++;
        }
        if ($this->email_verified_at != null) {
            $attributes['verify_email'] = true;
            $progress++;
        }


        $attributes['progress_percentage'] = (($progress / count($attributes)) * 100) . '%';
        return $attributes;
    }

    public function licences()
    {
        return $this->hasMany(License::class , 'user_id');
    }
}
