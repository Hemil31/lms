<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookCreateRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Services\BookServices;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * BookController
 *
 * Handles requests related to book management.
 */
class BookController extends Controller
{

    use JsonResponseTrait;

    /**
     * Constructs a new BookController instance.
     *
     * @param \App\Services\BookServices $bookServices The book services instance.
     */
    public function __construct(
        protected BookServices $bookServices
    ) {
        //
    }

    /**
     * Retrieves a list of all books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->bookServices->getAllBook();
            return $this->successResponse($data, 'book.fetch_all', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during fetch' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Creates a new book.
     *
     * @param \App\Http\Requests\Book\BookCreateRequest $request The book creation request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookCreateRequest $request): JsonResponse
    {
        $data = $request->all();
        try {
            $data = $this->bookServices->createBook($data);
            return $this->successResponse($data, 'book.create', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during register' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Retrieves a specific book by its UUID.
     *
     * @param string $bookUuid The UUID of the book.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $bookUuid): JsonResponse
    {
        try {
            $data = $this->bookServices->getBook($bookUuid);
            return $this->successResponse($data, 'book.fetch', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during fetch' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Updates an existing book by its UUID.
     *
     * @param \App\Http\Requests\Book\BookUpdateRequest $request The book update request.
     * @param string $bookUuid The UUID of the book.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BookUpdateRequest $request, string $bookUuid): JsonResponse
    {
        try {
            $data = $this->bookServices->updateBook($bookUuid, $request->all());
            return $this->successResponse($data, 'book.update', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during update' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Deletes a book by its UUID.
     *
     * @param string $bookUuid The UUID of the book.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $bookUuid): JsonResponse
    {
        try {
            $this->bookServices->deleteBook($bookUuid);
            return $this->successResponse(null, 'book.delete', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during delete' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Searches for books based on the provided search criteria.
     *
     * @param \Illuminate\Http\Request $request The search request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->bookServices->searchBook($request->all());
            return $this->successResponse($data, 'book.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during search' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Imports book data from an Excel file.
     *
     * @param \Illuminate\Http\Request $request The request containing the Excel file.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        try {
            $file = $request->file('file');
            $this->bookServices->bookImport($file);
            return $this->successResponse(null, 'book.import', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during import' . $e->getMessage(), statusCode: 500);
        }
    }


    /**
     * Exports book data to an Excel file.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        try {
            $this->bookServices->bookExport();
            return $this->successResponse(null, 'book.export', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during export' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Retrieves the book chart data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookChart(): JsonResponse
    {
        try {
            $data = $this->bookServices->getBookChart();
            return $this->successResponse($data, 'book.export', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during export' . $e->getMessage(), statusCode: 500);
        }
    }
}

