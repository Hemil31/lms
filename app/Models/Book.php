<?php

namespace App\Models;

use App\Enums\BookStatusEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Book extends BaseModel
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publication_date',
        'status'
    ];

    protected $hidden = [
        'deleted_at',
        'search_vector'
    ];

    protected $casts = [
        'publication_date' => 'date',
        'status' => BookStatusEnum::class
    ];

    /**
     * Define activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title','author','isbn','publication_date','status'])
            ->useLogName('Books')
            ->setDescriptionForEvent(fn(string $eventName) => "Book record has been {$eventName}");
    }

    /**
     * Summary of boot
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'isbn')) {
                $model->isbn = str_pad(random_int(0, 9999999999999), 13, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Summary of borrowingRecords
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowingRecords(): HasMany
    {
        return $this->hasMany(BorrowingRecords::class);
    }

}
