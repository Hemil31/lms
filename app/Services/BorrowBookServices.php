<?php

namespace App\Services;
use App\Enums\BookStatusEnum;
use App\Enums\RoleEnum;
use App\Notifications\BorrowBookNotificaiton;
use App\Notifications\BorrowBookReturnNotificaiton;
use App\Notifications\OverdueNotification;
use App\Repositories\BookRepository;
use App\Repositories\BorrowBookRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Service class responsible for handling borrow book operations.
 */
class BorrowBookServices
{
    /**
     * Constructs a new BorrowBookServices instance.
     *
     * @param BorrowBookRepository $borrowBookRepository The borrow book repository instance.
     * @param BookRepository $bookRepository The book repository instance.
     */
    public function __construct(
        protected BorrowBookRepository $borrowBookRepository,
        protected BookRepository $bookRepository,
    ) {
        //
    }

    /**
     * Creates a new borrow book record.
     *
     * @param array $data The data for creating a new borrow book record.
     * @return mixed
     */
    public function borrowBookCreate($data)
    {
        $response = [];
        try {
            DB::beginTransaction();
            $book = $this->bookRepository->findByUuid($data['book_id']);
            if ($book->status->name === BookStatusEnum::NotAvailable->name) {
                $response = [
                    'success' => false,
                    'message' => 'book.already_borrowed',
                ];
            }
            $user = auth()->user();
            $data = array_merge($data, [
                'user_id' => $user->id,
                'book_id' => $book->id,
                'due_date' => $data['due_date']
            ]);
            $borrowLimit = Config::get('library.borrow_limit');
            $currentBorrowedCount = $this->borrowBookRepository->findByColumn([
                ['user_id', '=', $user->id],
                ['returned_at', '=', null]
            ])->count();
            if ($currentBorrowedCount >= $borrowLimit) {
                $response = [
                    'success' => false,
                    'message' => 'book.limit',
                ];
            }
            if (!empty($response)) {
                DB::rollBack();
                return $response;
            }
            $borrowedBook = $this->borrowBookRepository->create($data);
            $this->bookRepository->updateByUuid($book->uuid, ['status' => '0']);
            DB::commit();
            $notificationData = [
                'name' => $user->name,
                'title' => $book->title,
                'duedate' => $data['due_date']
            ];
            $user->notify(new BorrowBookNotificaiton($notificationData));
            return $response = [
                'success' => true,
                'data' => $borrowedBook,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'An error occurred during borrow register' . $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieves a list of borrowed books for the current user or all borrowed books if the user has the admin role.
     *
     * @return mixed
     */
    public function getBook()
    {
        $id = auth()->user();
        if ($id->role_id === RoleEnum::UserID->value) {
            return $this->borrowBookRepository->findByColumn([
                ['user_id', '=', $id->id],
                ['returned_at', '=', null]
            ]);
        }
        return $this->borrowBookRepository->findByColumn([['returned_at', '=', null]])->load('book:id,title', 'user:id,name,email');
    }

    /**
     * Updates an existing borrow book record to indicate that the book has been returned.
     *
     * @param string $borrowUuid The UUID of the borrowed book.
     * @param array $data The data containing the returned_at timestamp.
     * @return mixed
     */
    public function updateReturnBorrow(string $borrowUuid)
    {
        $borrowedBook = $this->borrowBookRepository->findByUuid($borrowUuid);
        if ($borrowedBook->returned_at) {
            return [
                'success' => false,
                'message' => 'book.return',
            ];
        }
        $penalty = ($returnedAt = now()) > $borrowedBook->due_date
            ? $returnedAt->diffInDays($borrowedBook->due_date) * Config::get('library.penalty_fee')
            : 0;
        $borrowedBook->update([
            'returned_at' => $returnedAt,
            'penalty' => $penalty,
        ]);
        $borrowedBook->user->notify(new BorrowBookReturnNotificaiton([
            'name' => $borrowedBook->user->name,
            'title' => $borrowedBook->book->title,
            'penalty' => $penalty,
        ]));
        $this->bookRepository->updateById($borrowedBook->book_id, ['status' => '1']);
        return [
            'success' => true,
            'data' => $borrowedBook,
        ];
    }

    /**
     * Retrieves a list of all borrowed books history.
     *
     * @return mixed
     */
    public function getBorrowHistory()
    {
        $id = auth()->user();
        if ($id->role_id === RoleEnum::UserID->value) {
            return $this->borrowBookRepository->findByColumn([
                ['user_id', '=', $id->id],
                ['returned_at', '!=', null]
            ]);
        }
        return $this->borrowBookRepository->findByColumn([['returned_at', '!=', null]])->load('book:id,title', 'user:id,name,email');
    }

    /**
     * Retrieves a list of borrowed books for a specific book with user detail.
     *
     * @param string $bookUuid The UUID of the book.
     * @return mixed
     */
    public function getBorrowedBookDetails($bookUuid)
    {
        return $this->bookRepository->findByUuid($bookUuid)->load('borrowingRecords.user:id,name,email');
    }

    /**
     * Searches for Borrow based on the provided data.
     *
     * @param array $data The search criteria.
     * @return mixed
     */
    public function searchBorrow(array $data)
    {
        $search = [];
        if (isset($data['searchTerm'])) {
            $search = $this->borrowBookRepository->searchBorrowingRecords($data['searchTerm']);
        }
        if (isset($data['due_date'])) {
            $search = $this->borrowBookRepository->findByColumn([
                ['due_date', '<=', $data['due_date']],
                ['returned_at', '=', null]
            ]);
        }
        return $search;
    }

    /**
     * Sends overdue notifications to users who have not returned their borrowed books.
     *
     * @return void
     */
    public function sendOverdueNotifications()
    {
        $twoDaysAgo = Carbon::now()->subDays(2)->format('Y-m-d');
        $overdueBooks = $this->borrowBookRepository->findByColumn([
            ['due_date', '<=', $twoDaysAgo],
            ['returned_at', '=', null]
        ])->load('user:id,name', 'book:id,title');
        foreach ($overdueBooks as $overdue) {
            $data = [
                'name' => $overdue->user->name,
                'bookTitle' => $overdue->book->title,
                'dueDate' => $overdue->due_date->format('Y-m-d'),
            ];
            $overdue->user->notify(new OverdueNotification($data));
        }
    }

    /**
     * Generates a report of borrowing history by user or book.
     *
     * @param array $data The data for generating the report.
     * @return mixed
     */
    public function generateBorrowingReport(array $data)
    {
        $search = [];
        if (isset($data['searchTerm'])) {
            $search = $this->borrowBookRepository->searchBorrowingRecords($data['searchTerm'])->where('returned_at', '!=', null);
        }
        return $search;
    }
}
