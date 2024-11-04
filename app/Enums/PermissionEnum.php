<?php

namespace App\Enums;

/**
 * Class AdminEnum
 *
 * Enumeration class for permission related values.
 */
enum PermissionEnum
{
    case Slugs;
    /**
     * The permission slug array.
     */

    public function getAll(): array
    {
        return match($this) {
            self::Slugs => [
                "user" => [
                    'create' => 'user-create',
                    'update' => 'user-update',
                    'delete' => 'user-delete',
                    'view' => 'user-view',
                    'list'=> 'user-list',
                ],
                "book" => [
                    'create' => 'book-create',
                    'update' => 'book-update',
                    'delete' => 'book-delete',
                    'view' => 'book-view',
                    'list'=> 'book-list',
                    'export'=> 'book-export',
                ],

            ]
        };
    }
}
