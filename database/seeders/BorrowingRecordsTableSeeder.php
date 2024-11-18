<?php

namespace Database\Seeders;

use App\Models\BorrowingRecords;
use Illuminate\Database\Seeder;

class BorrowingRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowingRecords::factory()->count(10)->create();
    }
}
