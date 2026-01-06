<?php

namespace App\Services;

use App\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseService
{
    protected Repository $repository;
    public array $viewData = [];

    public function __construct()
    {
        if (property_exists($this, 'repositoryName')) {
            $this->repository = resolve($this->repositoryName);
        }
    }

    public function __get($property)
    {
        return $this->viewData[$property] ?? null;
    }

    public function __set(string $property, $data): void
    {
        $this->viewData[$property] = $data;
    }

    public function __isset($property): bool
    {
        return isset($this->viewData[$property]);
    }

    public function all(
        bool $paginate = false,
        array $allowedFilters = [],
        array $allowedSorts = [],
        array $load = [],
        ?callable $callback = null
    ): Collection|LengthAwarePaginator {
        $query = QueryBuilder::for($this->repository->modelName);

        if (\count($allowedFilters) > 0) {
            $query = $query->allowedFilters($allowedFilters);
        }

        if (\count($allowedSorts) > 0) {
            /**
             * Add default sort to whatever is passed in first in the array.
             * This is useful for when you want to sort by a default value
             * but still allow the user to sort by other values.
             * using '-' in front of the value will sort descending.
             */
            $query = $query->defaultSort('-' . $allowedSorts[0]);
            $query = $query->allowedSorts($allowedSorts);
        }

        if (\count($load) > 0) {
            $query = $query->allowedIncludes($load);
        }

        if (is_callable($callback)) {
            $query = $callback($query);
        }

        return $paginate ? $query->paginate(request('limit', 15)) : $query->get();
    }

    public function find(array|string $ids, array $load = [], array $filters = []): mixed
    {
        $query = QueryBuilder::for($this->repository->model);

        if (count($load) > 0) {
            $query = $query->allowedIncludes($load);
        }

        if (count($filters) > 0) {
            $query = $query->allowedFilters($filters);
        }

        return $query->findOrFail($ids);
    }

    public function __call($method, $parameters)
    {
        return $this->repository->$method(...$parameters);
    }
}
