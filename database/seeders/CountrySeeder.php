<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/seeders/data/country.json");
        $countries = json_decode($json);
        foreach ($countries as $key => $value) {
            $new_country = Country::query()->create(['name' => $key]);
            foreach ($value as $city) {
                City::query()->insert(['name' => json_encode(['en' => $city]), 'country_id' => $new_country->id]);
            }
        }


    }
}
