<?php
namespace App\Services;
use App\Exports\BookExportClass;
use App\Imports\BookImpotClass;
use App\Repositories\BookRepository;
use Maatwebsite\Excel\Facades\Excel;

/**
 * BookServices
 *
 * Provides services related to book management.
 */
class BookServices
{
    /**
     * Constructs a new BookServices instance.
     *
     * @param \App\Repositories\BookRepository $bookRepository The book repository instance.
     */
    public function __construct(
        protected BookRepository $bookRepository
    ) {
        //
    }

    /**
     * Creates a new book.
     *
     * @param array $data The book data.
     * @return mixed
     */
    public function createBook($data)
    {

        return $this->bookRepository->create($data);
    }

    /**
     * Updates an existing book by its UUID.
     *
     * @param string $bookUuid The UUID of the book.
     * @param array $data The updated book data.
     * @return mixed
     */
    public function updateBook(string $bookUuid, $data)
    {
        return $this->bookRepository->updateByUuid($bookUuid, $data);

    }

    /**
     * Deletes a book by its UUID.
     *
     * @param string $bookUuid The UUID of the book.
     * @return mixed
     */
    public function deleteBook(string $bookUuid)
    {
        return $this->bookRepository->deleteByUuid($bookUuid);
    }

    /**
     * Retrieves all books.
     *
     * @return mixed
     */
    public function getAllBook()
    {
        return $this->bookRepository->paginate();
    }

    /**
     * Retrieves a book by its UUID.
     *
     * @param string $userUuid The UUID of the book.
     * @return mixed
     */
    public function getBook(string $userUuid)
    {
        return $this->bookRepository->findByUuid($userUuid);
    }

    /**
     * Searches for books based on the provided data.
     *
     * @param array $data The search criteria.
     * @return mixed
     */
    public function searchBook(array $data)
    {
        $search = [];
        if (isset($data['search_terms'])) {
            $search = $this->bookRepository->search($data['search_terms'], 'search_vector');
        }
        if (isset($data['status'])) {
            $search = $this->bookRepository->filter('status', $data['status']);
        }
        if (isset($data['title'])) {
            $search = $this->bookRepository->filter('title', $data['title']);
        }
        if (isset($data['isbn'])) {
            $search = $this->bookRepository->filter('isbn', $data['isbn']);
        }
        if (isset($data['author'])) {
            $search = $this->bookRepository->filter('author', $data['author']);
        }

        if($search==[]){
            return response()->json(['message' => 'No record found'], 404);
        }
        return $search;
    }

    /**
     * Imports book data from an Excel file.
     *
     * @param string $file The path to the Excel file.
     *
     * @return void
     */
    public function bookImport($file): void
    {
        Excel::import(new BookImpotClass, $file);
    }

    /**
     * Exports book data to an Excel file.
     *
     */
    public function bookExport()
    {
        return Excel::download(new BookExportClass, 'users.xlsx');
    }
}
