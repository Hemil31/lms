<?php

namespace App\Repositories;
use App\Models\BorrowingRecords;

class BorrowBookRepository extends BaseRepository
{

    /**
     * Summary of __construct
     */
    public function __construct(BorrowingRecords $borrowingRecords)
    {
        $this->model = $borrowingRecords;
    }

     /**
     * Search for borrowing records by user name or book title.
     *
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchBorrowingRecords(string $searchTerm)
    {
        return $this->model->with(['user', 'book'])
            ->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('book', function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            })
            ->get();
    }

}
