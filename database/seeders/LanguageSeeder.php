<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [];
        $languages[] = ['name' =>json_encode( ['en' => 'english', 'ar' => 'الإنجليزية']), 'iso_code' => 'en'];
        $languages[] = ['name' => json_encode(['en' => 'arabic', 'ar' => 'العربية']), 'iso_code' => 'ar'];
        Language::query()->insert($languages);
    }
}
