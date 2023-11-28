<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super_admin = Role::query()->create(['name' => 'super admin']);
        $super_admin->syncPermissions(Permission::query()->pluck('name')->toArray());

        $writer = Role::query()->create(['name' => 'writer']);
        $writer->syncPermissions(['language management']);

    }
}
