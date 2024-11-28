<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        User::create([
            'name' => 'Hemil',
            'email' => 'hemil@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
        User::create([
            'name' => 'jay Dudhat',
            'email' => 'jay@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'ketan patel',
            'email' => 'ketan@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
        User::create([
            'name' => 'kishan',
            'email' => 'kishan@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
        User::create([
            'name' => 'hemal',
            'email' => 'hemal@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);

        User::create([
            'name' => 'gajendra',
            'email' => 'gajendra@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 3,
        ]);
        User::factory(50)->create();
    }
}
