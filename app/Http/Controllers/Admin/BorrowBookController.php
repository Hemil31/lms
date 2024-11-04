<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowBookCreate;
use App\Services\BorrowBookServices;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller class responsible for handling borrow book operations.
 */
class BorrowBookController extends Controller
{
    use JsonResponseTrait;

    /**
     * The borrow book services instance.
     *
     * @param \App\Services\BorrowBookServices $borrowBookServices The borrowBookServices services instance.
     */
    public function __construct(
        protected BorrowBookServices $borrowBookServices
    ) {
        //
    }

    /**
     * Creates a new borrow book record.
     *
     * @param BorrowBookCreate $request The request containing borrow book data.
     * @return JsonResponse
     */
    public function store(BorrowBookCreate $request): JsonResponse
    {
        try {
            $result = $this->borrowBookServices->borrowBookCreate($request->all());
            
            if (!$result['success']) {
                return $this->errorResponse($result['message'], 400);
            }
            return $this->successResponse($result, 'book.borrow', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during register' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Retrieves details of a specific borrowed book.
     *
     * @return JsonResponse
     */
    public function show()
    {
        try {
            $data = $this->borrowBookServices->getBook();
            return $this->successResponse($data, 'book.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during borrow book retrieval:' . $e->getMessage(), statusCode: 500);
        }
    }


    /**
     * Updates a borrowed book record to indicate it has been returned.
     *
     * @param Request $request The request containing return information.
     * @param string $borrowUuid The UUID of the borrowed book.
     * @return JsonResponse
     */
    public function update(string $borrowUuid): JsonResponse
    {
        try {
            $result = $this->borrowBookServices->updateReturnBorrow($borrowUuid);
            if (!$result['success']) {
                return $this->errorResponse($result['message'], 400);
            }
            return $this->successResponse($result, 'book.return', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during borrow book return: ' . $e->getMessage(), 500);
        }
    }

    /*
     * Retrieves a list of all borrowed books.
     *
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        try {
            $data = $this->borrowBookServices->getBorrowHistory();
            return $this->successResponse($data, 'book.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during borrow book retrieval:' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Retrieves a list of borrowed books for a specific book with user detail.
     *
     * @param string $bookUuid The UUID of the book.
     * @return JsonResponse
     */
    public function borrowedBooks(string $bookUuid): JsonResponse
    {
        try {
            $book = $this->borrowBookServices->getBorrowedBookDetails($bookUuid);
            return $this->successResponse($book, 'book.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during book with user borrow book retrieval' . $e->getMessage());
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
            $data = $this->borrowBookServices->searchBorrow($request->all());
            return $this->successResponse($data, 'book.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during search' . $e->getMessage(), statusCode: 500);
        }
    }
}
