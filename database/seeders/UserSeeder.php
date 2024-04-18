<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Talent;
use App\Models\Coach;
use App\Models\Scout;
use App\Models\User;
use Faker\Provider\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $test_users = [
            'users' => ['adham', 'mothana', 'areen', 'ola'],
            'user_types' => ['talent', 'coache', 'club', 'scout'],
        ];
        foreach ($test_users['users'] as $user) {
            foreach ($test_users['user_types'] as $key => $type) {
                $data = [
                    'name' => $user . '_' . $type,
                    'parent_position_id' => 1,
                    'sport_id' => 1,
                    'position_id' => 11,
                    'height' => 170,
                    'wight' => 67,
                    'country_id' => 1,
                    'city_id' => 1,
                    'mobile_number' => 962782999694,
                    'years_of_experience' => rand(1, 9),
                    'year_founded' => rand(2010, 2024),
                    'preferred_foot' => rand(0, 1) ? 'right' : 'left',
                ];
                $new_user = User::query()->create([
                    'name' => $user . '_' . $type,
                    'first_name' => $user,
                    'last_name' => $type,
                    'email' => $user . '_' . $type . '@triplef.group',
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'user_type_id' => $key + 1,
                ]);

                $data['mobile_number'] = 962782999694 . rand(1, 9);
                $data['user_id'] = $new_user->id;
                DB::table($type . 's')->insertOrIgnore($this->unsetUnwantedFields($type, $data));
            }
        }
    }

    public function unsetUnwantedFields($type, $data)
    {
        if ($type == 'talent') {
            unset($data['name']);
            unset($data['years_of_experience']);
            unset($data['year_founded']);


        }
        if ($type == 'coache') {
            unset($data['parent_position_id']);
            unset($data['position_id']);
            unset($data['name']);
            unset($data['height']);
            unset($data['wight']);
            unset($data['year_founded']);
            unset($data['preferred_foot']);
        }
        if ($type == 'scout') {
            unset($data['parent_position_id']);
            unset($data['position_id']);
            unset($data['name']);
            unset($data['height']);
            unset($data['wight']);
            unset($data['year_founded']);
            unset($data['preferred_foot']);
        }
        if ($type == 'club') {
            unset($data['parent_position_id']);
            unset($data['position_id']);
            unset($data['years_of_experience']);
            unset($data['height']);
            unset($data['wight']);

            unset($data['preferred_foot']);
            unset($data['city_id']);
        }
        return $data;
    }
}
