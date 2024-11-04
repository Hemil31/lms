<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserAddPermissionSeeder::class);
        $this->call(BookTableSeeder::class);
        $this->call(BookAddPermissionSeeder::class);
        $this->call(BorrowingRecordsTableSeeder::class);

    }
}
