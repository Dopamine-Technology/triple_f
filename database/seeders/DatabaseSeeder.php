<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            TranslationsSeeder::class,
            UserTypeSeeder::class,
            UserSeeder::class,
            SportSeeder::class,
            positionsSeeder::class,
            CountrySeeder::class,
        ]);
    }
}
