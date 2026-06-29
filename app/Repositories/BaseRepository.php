<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    public function all(): Collection
    {
        return $this->query->get();
    }

    public function find(int $id): ?Model
    {
        return $this->query->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->query->findOrFail($id);
    }

    public function findBy(string $column, mixed $value): ?Model
    {
        return $this->query->where($column, $value)->first();
    }

    public function where(string $column, mixed $value): Collection
    {
        return $this->query->where($column, $value)->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    public function with(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function withTrashed(): self
    {
        $this->query->withTrashed();
        return $this;
    }

    public function onlyTrashed(): self
    {
        $this->query->onlyTrashed();
        return $this;
    }

    public function restore(int $id): bool
    {
        return $this->query->withTrashed()->find($id)?->restore() ?? false;
    }

    public function forceDelete(int $id): bool
    {
        return $this->query->withTrashed()->find($id)?->forceDelete() ?? false;
    }

    public function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }
}
