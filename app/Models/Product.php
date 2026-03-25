<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'gold_purity',
        'weight',
        'making_cost',
        'gold_price_per_gram',
        'price',
        'special_price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
        'images',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:3',
            'making_cost' => 'decimal:2',
            'gold_price_per_gram' => 'decimal:2',
            'price' => 'decimal:2',
            'special_price' => 'decimal:2',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->special_price ?? $this->price;
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return $this->getDefaultImage();
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/products/' . $this->image);
    }

    public function getDefaultImage(): string
    {
        return match ($this->category?->slug) {
            'rings' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&h=600&fit=crop',
            'necklaces' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&h=600&fit=crop',
            'bracelets' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&h=600&fit=crop',
            'earrings' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&h=600&fit=crop',
            'watches' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=600&fit=crop',
            default => 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?w=600&h=600&fit=crop',
        };
    }
}
