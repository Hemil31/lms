<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Str;

/**
 * BaseModel
 *
 * Provides a base class for all models to extend from.
 */
class BaseModel extends Model
{
    use HasFactory;

    protected $commonCasts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
    ];

    /**
     * Merge common casts with the casts from child models.
     *
     * @return array
     */
    public function getCasts()
    {
        return array_merge($this->commonCasts, $this->casts);
    }

    /**
     * Generates a UUID for the model before it is created.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model being created.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'uuid')) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * Summary of scopeSearch
     * @param mixed $query
     * @param mixed $search
     * @param mixed $column
     * @return mixed
     */
    public function scopeSearch($query, $search, $column)
    {
        return $query->whereRaw($column . ' @@websearch_to_tsquery(\'english\', ?)', [$search]);
    }

}
