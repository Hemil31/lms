<?php

namespace App\Repositories;

use App\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * BaseRepository provides a base implementation for repository pattern using Eloquent models.
 */
class BaseRepository implements BaseRepositoryInterface
{

    /**
     * BaseRepository constructor.
     *
     * @param Model $model The Eloquent model instance.
     */
    public function __construct(
        protected Model $model
    ) {
        //
    }

    /**
     * Finds a model by its UUID.
     *
     * @param string $modelUuid
     * @return Model|null
     */
    public function findByUuid(string $modelUuid): ?Model
    {
        $find = $this->model->where('uuid', $modelUuid)->first();
        if (!$find) {
            throw new \Exception('Model not found for the provided UUID: ' . $modelUuid);
        }
        return $find;
    }

    /**
     * Creates a new model and persists it to the database.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Updates an existing model in the database by UUID.
     *
     * @param string $modelUuid
     * @param array $data
     * @return Model|null
     */
    public function updateByUuid(string $modelUuid, array $data): ?Model
    {
        $model = $this->findByUuid($modelUuid);
        $model->update($data);
        return $model;
    }

    /**
     * Deletes a model from the database by its UUID.
     *
     * @param string $modelUuid
     * @return bool
     */
    public function deleteByUuid(string $modelUuid): bool
    {
        return $this->findByUuid($modelUuid)->delete();
    }

    /**
     * Gets the query builder instance.
     *
     * @param array $columns
     * @param array $relations
     * @return Builder
     */
    public function query(array $columns = ['*'], array $relations = []): Builder
    {
        return $this->model->query()->select($columns)->with($relations);
    }

    /**
     * Retrieves all models from the database.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->query($columns, $relations)->get();
    }

    /**
     * Filters the query builder based on the given columns and request.
     *
     * @param array $columns
     * @param string $request
     */
    public function filter($columns, string $request)
    {
        return $this->model->query()->where($columns, 'ILIKE', '%' . $request . '%')->get();
    }

    /**
     * Performs full-text search on the specified columns using the given request.
     *
     * @param array $columns
     * @param string $request
     */
    public function search($search, $column)
    {
        return $this->model->search($search, $column)->get();
    }

    /**
     * Find model by column.
     *
     * @param array $column
     */
    public function findByColumn($column=[])
    {
        return $this->model->where($column)->get();
    }

    /**
     * Find model by id.
     *
     * @param string $modelId
     * @return Model|null
     */
    public function findById(string $modelId): ?Model
    {
        $find = $this->model->find($modelId);
        if (!$find) {
            throw new \Exception('Model not found for the provided Id: ' . $modelId);
        }
        return $find;
    }

    /**
     * Update existing model by id.
     *
     * @param string $modelId
     * @param array $data
     * @return Model|null
     */
    public function updateById(string $modelId, array $data): ?Model
    {
        $model = $this->findById($modelId);
        $model->update($data);
        return $model;
    }
}
