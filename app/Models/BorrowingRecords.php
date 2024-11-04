<?php

namespace App\Models;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class BorrowingRecords extends BaseModel
{
    use LogsActivity;
    protected $table = 'borrowing_records';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'penalty',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime:Y-m-d',
        'due_date' => 'datetime:Y-m-d',
        'returned_at' => 'datetime:Y-m-d',
    ];

    /**
     * Define activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'book_id', 'borrowed_at', 'due_date', 'returned_at', 'penalty'])
            ->useLogName('borrowing_records')
            ->setDescriptionForEvent(fn(string $eventName) => "Borrowing record has been {$eventName}");
    }

    /**
     * A borrowing record belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A borrowing record belongs to a book
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

}
