<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    protected static ?int $tenantId = null;

    public static function addTenant(int $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    public static function getTenantId(): ?int
    {
        return self::$tenantId;
    }

    public static function clearTenant(): void
    {
        self::$tenantId = null;
    }

    public function apply(Builder $builder, Model $model): void
    {
        if (self::$tenantId && $this->shouldApplyScope($model)) {
            $builder->where('tenant_id', self::$tenantId);
        }
    }

    protected function shouldApplyScope(Model $model): bool
    {
        // Don't apply to tenant-related models
        $excludedModels = [
            Tenant::class,
            SubscriptionPlan::class,
            User::class,
        ];

        foreach ($excludedModels as $excludedModel) {
            if ($model instanceof $excludedModel) {
                return false;
            }
        }

        // Check if model has tenant_id column
        return $model->getConnection()->getSchemaBuilder()->hasColumn(
            $model->getTable(),
            'tenant_id'
        );
    }
}
