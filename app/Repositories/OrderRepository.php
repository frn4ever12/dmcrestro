<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model->byStatus($status)->get();
    }

    public function getByPaymentStatus(string $status): Collection
    {
        return $this->model->byPaymentStatus($status)->get();
    }

    public function getTodayOrders(): Collection
    {
        return $this->model->today()->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->byDateRange($startDate, $endDate)->get();
    }

    public function getByBranch(int $branchId): Collection
    {
        return $this->model->where('branch_id', $branchId)->get();
    }

    public function getByCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function getByWaiter(int $waiterId): Collection
    {
        return $this->model->where('waiter_id', $waiterId)->get();
    }

    public function getPendingOrders(): Collection
    {
        return $this->model->byStatus('pending')->get();
    }

    public function getCompletedOrders(): Collection
    {
        return $this->model->byStatus('completed')->get();
    }

    public function getCancelledOrders(): Collection
    {
        return $this->model->byStatus('cancelled')->get();
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['restaurant_id'])) {
            $query->where('restaurant_id', $filters['restaurant_id']);
        }

        if (isset($filters['tenant_id'])) {
            $query->where('tenant_id', $filters['tenant_id']);
        }

        if (isset($filters['order_type'])) {
            $query->where('order_type', $filters['order_type']);
        }

        if (isset($filters['from_date']) && isset($filters['to_date'])) {
            $query->whereBetween('order_date', [$filters['from_date'], $filters['to_date']]);
        }

        if (isset($filters['search'])) {
            $query->where('invoice_number', 'like', "%{$filters['search']}%");
        }

        return $query->with(['customer', 'table', 'waiter', 'cashier', 'items'])->paginate($perPage);
    }

    public function getTodaySales(int $branchId = null): float
    {
        $query = $this->model->today()->where('status', 'completed');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->sum('total_amount');
    }

    public function getTodayOrderCount(int $branchId = null): int
    {
        $query = $this->model->today();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->count();
    }
}
