<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Class BookExportClass
 *
 * This class exports book data from the database to an Excel file.
 */
class BookExportClass implements FromCollection, WithHeadings
{
    
    /**
     * Collection method
     *
     * This method retrieves the book data from the database and returns it as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::select("id", "title", "author", "isbn", "publication_date", "status")->get();
    }


    /**
     * Headings method
     *
     * This method defines the column headings for the Excel export.
     *
     * @return array
     */
    public function headings(): array
    {
        return ["Id", "Title", "Author", "isbn", "Publication_date", "Status"];
    }
}
