<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class BookImportClass
 *
 * This class imports book data from an Excel file into the database.
 */
class BookImpotClass implements ToModel,WithHeadingRow
{
     /**
     * Model method
     *
     * This method maps the data from each row of the Excel file to a Book model instance.
     *
     * @param array $row The data from a row of the Excel file.
     * @return Book|null A Book model instance or null if the row does not contain valid data.
     */
    public function model(array $row)
    {
        if (isset($row['title']) && isset($row['author'])) {
            return new Book([
                'title' => $row['title'],
                'author' => $row['author'],
                'status' => $row['status'],
                'publication_date' => $row['publication_date'],
            ]);
        }
    }
}
