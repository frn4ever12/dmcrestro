<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait HasTenant
{
    protected static function bootHasTenant(): void
    {
        static::creating(function ($model) {
            if (empty($model->tenant_id) && app()->has('current_tenant')) {
                $model->tenant_id = app('current_tenant')->id;
            }
        });

        static::addGlobalScope(new TenantScope());
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
