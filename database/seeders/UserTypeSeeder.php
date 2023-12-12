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
        $types = array();
        $types[] = ['name' => json_encode(['en' => 'talent', 'ar' => 'موهبة'])];
        $types[] = ['name' => json_encode(['en' => 'couch', 'ar' => 'مدرب'])];
        $types[] = ['name' => json_encode(['en' => 'club', 'ar' => 'نادي'])];
        $types[] = ['name' => json_encode(['en' => 'scout', 'ar' => 'مكتشف مواهب'])];
        UserType::query()->insert($types);

    }
}
