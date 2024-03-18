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
        foreach ($countries  as $one) {
            $new_country = Country::query()->create(['name' => $one->name , 'iso_code'=> $one->iso_code ?? '' , 'image'=> $one->image]);
            foreach ($one->cities as $city) {
                City::query()->insert(['name' => json_encode(['en' => $city]), 'country_id' => $new_country->id]);
            }
        }


    }
}
