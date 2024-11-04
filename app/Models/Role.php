<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Role extends Model
{

    protected $table = 'roles';

    protected $fillable = ['name'];

    public $timestamps = false;

    /**
     * Defines a many-to-many relationship between roles and permissions.
     *
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->using(PermissionRole::class);
    }

    /**
     * Defines a many-to-many relationship between roles and users.
     *
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
