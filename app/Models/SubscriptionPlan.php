<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'yearly_price',
        'trial_days',
        'max_restaurants',
        'max_branches',
        'max_users',
        'storage_limit_mb',
        'pos_module',
        'qr_menu_module',
        'inventory_module',
        'accounting_module',
        'hrm_module',
        'crm_module',
        'kitchen_display_module',
        'online_ordering_module',
        'delivery_module',
        'api_access',
        'white_label',
        'custom_domain',
        'priority_support',
        'is_active',
        'sort_order',
        'features',
        'enabled_modules',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'pos_module' => 'boolean',
        'qr_menu_module' => 'boolean',
        'inventory_module' => 'boolean',
        'accounting_module' => 'boolean',
        'hrm_module' => 'boolean',
        'crm_module' => 'boolean',
        'kitchen_display_module' => 'boolean',
        'online_ordering_module' => 'boolean',
        'delivery_module' => 'boolean',
        'api_access' => 'boolean',
        'white_label' => 'boolean',
        'custom_domain' => 'boolean',
        'priority_support' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'array',
        'enabled_modules' => 'array',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
