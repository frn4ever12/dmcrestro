<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'restaurant_id',
        'branch_id',
        'name',
        'number',
        'floor_id',
        'type',
        'capacity',
        'min_capacity',
        'status',
        'qr_code',
        'qr_code_url',
        'is_mergeable',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'min_capacity' => 'integer',
        'is_mergeable' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeByFloor($query, $floorId)
    {
        return $query->where('floor_id', $floorId);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }

    public function markAsOccupied(): void
    {
        $this->update(['status' => 'occupied']);
    }

    public function markAsReserved(): void
    {
        $this->update(['status' => 'reserved']);
    }
}
