<?php

namespace App\Repositories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RestaurantRepository extends BaseRepository
{
    public function __construct(Restaurant $model)
    {
        parent::__construct($model);
    }

    public function getByTenant(int $tenantId): Collection
    {
        return $this->model->byTenant($tenantId)->get();
    }

    public function getActiveRestaurants(): Collection
    {
        return $this->model->active()->get();
    }

    public function search(string $term, int $tenantId = null): Collection
    {
        $query = $this->model->query();

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->where('name', 'like', "%{$term}%")
            ->orWhere('slug', 'like', "%{$term}%")
            ->get();
    }

    public function paginateByTenant(int $tenantId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->byTenant($tenantId)->paginate($perPage);
    }
}
