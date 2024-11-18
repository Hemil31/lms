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

  /**
     * Returns a human-readable label for the role.
     *
     * @return string The label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdminID => 'Super Admin',
            self::AdminID => 'Admin',
            self::UserID => 'User',
        };
    }
}
