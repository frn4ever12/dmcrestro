<?php

namespace App\Repositories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MenuItemRepository extends BaseRepository
{
    public function __construct(MenuItem $model)
    {
        parent::__construct($model);
    }

    public function getAvailableItems(): Collection
    {
        return $this->model->available()->get();
    }

    public function getFeaturedItems(): Collection
    {
        return $this->model->featured()->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->byCategory($categoryId)->get();
    }

    public function getVegetarianItems(): Collection
    {
        return $this->model->vegetarian()->get();
    }

    public function getVeganItems(): Collection
    {
        return $this->model->vegan()->get();
    }

    public function search(string $term, int $restaurantId = null): Collection
    {
        $query = $this->model->query();

        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }

        return $query->where('name', 'like', "%{$term}%")
            ->orWhere('name_np', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->get();
    }

    public function paginateByRestaurant(int $restaurantId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('restaurant_id', $restaurantId)->paginate($perPage);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['restaurant_id'])) {
            $query->where('restaurant_id', $filters['restaurant_id']);
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_available'])) {
            $query->where('is_available', $filters['is_available']);
        }

        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        if (isset($filters['is_vegetarian'])) {
            $query->where('is_vegetarian', $filters['is_vegetarian']);
        }

        if (isset($filters['is_vegan'])) {
            $query->where('is_vegan', $filters['is_vegan']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('name_np', 'like', "%{$filters['search']}%");
            });
        }

        return $query->with('category')->paginate($perPage);
    }
}
