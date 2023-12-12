<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Sport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sports = array();
        $sports[] = ['name' => json_encode(['en' => 'football', 'ar' => 'كرة القدم'])];
        $sports[] = ['name' => json_encode(['en' => 'basketball', 'ar' => 'كرة السلة'])];
        Sport::query()->insert($sports);
    }
}
