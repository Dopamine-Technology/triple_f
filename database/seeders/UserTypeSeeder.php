<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_permissions = json_encode(
            [
                ['name' => 'view_talent', 'value' => true],
                ['name' => 'view_scout', 'value' => false],
                ['name' => 'view_club', 'value' => false],
                ['name' => 'react_to_video', 'value' => true],
            ]
        );

        $types = array();
        $types[] = ['name' => json_encode(['en' => 'talent', 'ar' => 'موهبة']), 'permissions' => $default_permissions];
        $types[] = ['name' => json_encode(['en' => 'couch', 'ar' => 'مدرب']), 'permissions' => $default_permissions];
        $types[] = ['name' => json_encode(['en' => 'club', 'ar' => 'نادي']), 'permissions' => $default_permissions];
        $types[] = ['name' => json_encode(['en' => 'scout', 'ar' => 'مكتشف مواهب']), 'permissions' => $default_permissions];
        UserType::query()->insert($types);

    }
}
