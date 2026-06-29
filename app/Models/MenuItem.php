<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'restaurant_id',
        'branch_id',
        'category_id',
        'name',
        'name_np',
        'slug',
        'description',
        'image',
        'price',
        'cost_price',
        'tax_rate',
        'discount_percentage',
        'barcode',
        'sku',
        'kitchen_section',
        'preparation_time',
        'recipe',
        'ingredients',
        'allergens',
        'calories',
        'is_vegetarian',
        'is_vegan',
        'is_spicy',
        'is_available',
        'is_featured',
        'available_from',
        'available_to',
        'available_days',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'preparation_time' => 'integer',
        'calories' => 'integer',
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_spicy' => 'boolean',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'datetime',
        'available_to' => 'datetime',
        'available_days' => 'array',
        'ingredients' => 'array',
        'allergens' => 'array',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function modifiers(): BelongsToMany
    {
        return $this->belongsToMany(Modifier::class, 'menu_item_modifier');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }

    public function scopeVegan($query)
    {
        return $query->where('is_vegan', true);
    }

    public function getDiscountedPrice(): float
    {
        if ($this->discount_percentage > 0) {
            return $this->price - ($this->price * ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    public function getMargin(): float
    {
        if ($this->cost_price > 0) {
            return (($this->price - $this->cost_price) / $this->cost_price) * 100;
        }
        return 0;
    }

    public function isAvailableNow(): bool
    {
        if (!$this->is_available) {
            return false;
        }

        $now = now();
        $day = strtolower($now->format('l'));

        if ($this->available_days && !in_array($day, $this->available_days)) {
            return false;
        }

        if ($this->available_from && $now < $this->available_from) {
            return false;
        }

        if ($this->available_to && $now > $this->available_to) {
            return false;
        }

        return true;
    }
}
