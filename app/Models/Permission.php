<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'module_name', 'sub_module_name', 'slug'];
    public $timestamps = false;

    /**
     * Defines a many-to-many relationship between permissions and roles.
     *
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->using(PermissionRole::class);
    }

}
