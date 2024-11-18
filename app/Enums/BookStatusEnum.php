<?php

namespace App\Enums;

/**
 * Class BookStatusEnum
 *
 * Enumeration class for Status values.
 */
enum BookStatusEnum: string
{
    case Available = "1";
    case NotAvailable = "0";

    /**
     * Returns a human-readable label for the book status.
     *
     * @return string The label for the book status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::NotAvailable => 'Not Available',
        };
    }
}
