<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BorrowingRecord>
 */
class BorrowingRecordsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIdsWithRole3 = User::where('role_id', 3)->pluck('id')->toArray();

        $availableBookIds = Book::where('status', 1)->pluck('id')->toArray();

        $borrowedAt = $this->faker->dateTimeThisYear();
        $dueDate = Carbon::instance($borrowedAt)->addDays(7);

        $returnedAt = $this->faker->boolean() ? $this->faker->dateTimeBetween($borrowedAt, $dueDate) : null;

        $bookId = $this->faker->randomElement($availableBookIds);

        Book::where('id', operator: $bookId)->update(['status' => 0]);

        return [
            'user_id' => $this->faker->randomElement($userIdsWithRole3),
            'book_id' => $bookId,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => $returnedAt,
        ];
    }
}
