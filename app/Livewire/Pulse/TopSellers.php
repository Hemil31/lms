<?php

namespace App\Livewire\Pulse;

use App\Models\Book;
use App\Models\BorrowingRecords;
use DB;
use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;

#[Lazy]
class TopSellers extends Card
{
    public function render()
    {

        $topSellers = BorrowingRecords::select('book_id', DB::raw('count(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->take(5) // Show top 5 books
            ->with('book') // Eager load the Book model to get book details like title
            ->get();
    
        // Return the view with top-selling books data
        return view('livewire.pulse.top-sellers', compact('topSellers'));
    }
}
