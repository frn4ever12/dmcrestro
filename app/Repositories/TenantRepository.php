<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TenantRepository extends BaseRepository
{
    public function __construct(Tenant $model)
    {
        parent::__construct($model);
    }

    public function getActiveTenants(): Collection
    {
        return $this->model->active()->get();
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->byStatus($status)->get();
    }

    public function getExpiredTenants(): Collection
    {
        return $this->model->where('subscription_ends_at', '<', now())->get();
    }

    public function getTrialTenants(): Collection
    {
        return $this->model->byStatus('trial')->get();
    }

    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('slug', 'like', "%{$term}%")
            ->get();
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['subscription_plan_id'])) {
            $query->where('subscription_plan_id', $filters['subscription_plan_id']);
        }

        return $query->paginate($perPage);
    }
}
