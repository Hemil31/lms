<?php

namespace App\Models;

class StripPayment extends BaseModel
{
    protected $table = 'strip_payments';
    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => self::DATE_FORMAT,
    ];

}
