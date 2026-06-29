<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'domain',
        'email',
        'phone',
        'logo',
        'address',
        'city',
        'country',
        'pan_number',
        'vat_number',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'subscription_plan_id',
        'settings',
        'max_users',
        'max_branches',
        'storage_used_mb',
        'storage_limit_mb',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot('role', 'permissions')
            ->withTimestamps();
    }

    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->subscription_ends_at && $this->subscription_ends_at->isPast());
    }

    public function canAddUser(): bool
    {
        return $this->users()->count() < $this->max_users;
    }

    public function canAddBranch(): bool
    {
        return $this->restaurants()->count() < $this->max_branches;
    }

    public function hasStorageSpace(int $sizeMb): bool
    {
        return ($this->storage_used_mb + $sizeMb) <= $this->storage_limit_mb;
    }

    public function getStorageUsagePercentage(): int
    {
        return ($this->storage_used_mb / $this->storage_limit_mb) * 100;
    }
}
