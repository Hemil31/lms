<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * BaseRepositoryInterface defines common operations for repositories.
 */
interface BaseRepositoryInterface
{
    /**
     * Find model by UUid.
     *
     * @param string $modelUuid
     * @return Model
     */
    public function findByUuid(
        string $modelUuid,
    ): ?Model;

    /**
     * Create a model.
     *
     * @param array $data Data to create a model.
     * @return Model|null Created model instance, or null on failure.
     */
    public function create(array $data): Model;

    /**
     * Update existing model.
     *
     * @param string $modelUuid
     * @param array $data
     * @return Model|null
     */
    public function updateByUuid(string $modelUuid, array $data): ?Model;

    /**
     * Delete model by uuid.
     *
     * @param string $modelUuid
     * @return bool
     */
    public function deleteByUuid(string $modelUuid): bool;

    /**
     * Get the query builder instance.
     *
     * @param array $columns   The columns to be selected.
     * @param array $relations The relationships to be eager loaded.
     *
     * @return Builder
     */
    public function query(array $columns = ['*'], array $relations = []): Builder;

    /**
     * Retrieve all models from the database.
     *
     * @param array $columns   The columns to be selected.
     * @param array $relations The relationships to be eager loaded.
     *
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Filters the query builder based on the given columns and request.
     *
     * @param array $columns
     * @param string $request
     */
    public function filter($columns, string $request);

    /**
     * Performs full-text search on the specified columns using the given request.
     *
     * @param array $columns
     * @param string $request
     */
    public function search($search, $column);

    /**
     * Find model by column.
     *
     * @param array $column
     */
    public function findByColumn($column = []);

    /**
     * Find model by id.
     *
     * @param string $modelId
     * @return Model|null
     */
    public function findById(string $modelId): ?Model;

    /**
     * Update existing model by id.
     *
     * @param string $modelId
     * @param array $data
     * @return Model|null
     */
    public function updateById(string $modelId, array $data): ?Model;

}
