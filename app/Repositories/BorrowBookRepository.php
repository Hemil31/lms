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
        return $this->model->with(['user:id,name', 'book:id,title'])
            ->whereHas('user', function ($query) use ($searchTerm) {
                $query->whereRaw('search_vector @@ websearch_to_tsquery(\'english\', ?)', [$searchTerm]);
            })
            ->orWhereHas('book', function ($query) use ($searchTerm) {
                $query->whereRaw('search_vector @@ websearch_to_tsquery(\'english\', ?)', [$searchTerm]);
            })
            ->get();
    }

}
