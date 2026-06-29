<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'owner_id',
        'name',
        'slug',
        'logo',
        'description',
        'pan_number',
        'vat_number',
        'phone',
        'email',
        'website',
        'address',
        'province',
        'district',
        'municipality',
        'ward',
        'latitude',
        'longitude',
        'invoice_prefix',
        'printer_type',
        'tax_rate',
        'service_charge_rate',
        'currency',
        'language',
        'nepali_date_enabled',
        'opening_time',
        'closing_time',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'tax_rate' => 'decimal:2',
        'service_charge_rate' => 'decimal:2',
        'nepali_date_enabled' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function fiscalYears(): HasMany
    {
        return $this->hasMany(FiscalYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function isOpen(): bool
    {
        $now = now()->format('H:i:s');
        return $now >= $this->opening_time && $now <= $this->closing_time;
    }

    public function generateInvoiceNumber(): string
    {
        $prefix = $this->invoice_prefix;
        $date = now()->format('Ymd');
        $lastOrder = $this->orders()->whereDate('order_date', today())->latest()->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->invoice_number, -4) + 1 : 1;
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }
}
