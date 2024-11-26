<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();

        $roles = ['SuperAdmin','Admin','User' ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }

    }
}
