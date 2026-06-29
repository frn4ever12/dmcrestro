<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TenantRepository;
use App\Repositories\RestaurantRepository;
use App\Repositories\OrderRepository;
use App\Repositories\MenuItemRepository;
use App\Models\Tenant;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\MenuItem;
use App\Services\TenantService;
use App\Services\OrderService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepository::class, function ($app) {
            return new TenantRepository(new Tenant());
        });

        $this->app->bind(RestaurantRepository::class, function ($app) {
            return new RestaurantRepository(new Restaurant());
        });

        $this->app->bind(OrderRepository::class, function ($app) {
            return new OrderRepository(new Order());
        });

        $this->app->bind(MenuItemRepository::class, function ($app) {
            return new MenuItemRepository(new MenuItem());
        });

        $this->app->bind(TenantService::class, function ($app) {
            return new TenantService($app->make(TenantRepository::class));
        });

        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService($app->make(OrderRepository::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
