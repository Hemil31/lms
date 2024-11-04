<?php

namespace App\Enums;

/**
 * Class RoleEnum
 *
 * Enumeration class for role values.
 */
enum RoleEnum: int {
    case SuperAdminID  = 1;
    case AdminID       = 2;
    case UserID        = 3;

    public function label(): string
    {
        return match ($this) {
            self::SuperAdminID => 'Super Admin',
            self::AdminID => 'Admin',
            self::UserID => 'User',
        };
    }
}
