<?php

namespace App\Repositories;
use App\Models\Book;

class BookRepository extends BaseRepository
{

    /**
     * Summary of __construct
     */
    public function __construct(Book $book)
    {
        $this->model = $book;
    }

}
