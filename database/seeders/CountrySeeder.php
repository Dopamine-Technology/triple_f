<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = array();
        $countries[] = ['name' => json_encode(['en' => 'Jordan', 'ar' => 'الأردن']), 'iso_code' => 'jo', 'order' => 1];
        $countries[] = ['name' => json_encode(['en' => 'Saudi Arabia', 'ar' => 'المملكة السعودية العربية']), 'iso_code' => 'ksa', 'order' => 2];
        $countries[] = ['name' => json_encode(['en' => 'Egypt', 'ar' => 'جمهورية مصر العربية']), 'iso_code' => 'eg', 'order' => 3];
        Country::query()->insert($countries);
    }
}
