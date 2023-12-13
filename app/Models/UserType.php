<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class UserType extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'user_types';
    public $translatable = ['name'];
    protected $casts = ['permissions' => 'array'];
    protected $guarded = [];
    protected $appends = ['selected_permissions'];
    public static $permisions_options = [
        'view_talent' => 'View Talent',
        'view_scout' => 'View Scout',
        'view_club' => 'View Club',
        'react_to_video' => 'React To Video',
    ];
    public function getSelectedPermissionsAttribute()
    {
        $selected_permissions = array();
        foreach ($this->permissions as $value) {

            if ($value['value']) {
                $selected_permissions[$value['name']] = Str::upper(str_replace('_', ' ', $value['name']));
            }
        }
        return $selected_permissions;
    }


}
