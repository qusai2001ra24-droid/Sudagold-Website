<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'movement_type',
        'quantity_change',
        'quantity_before',
        'quantity_after',
        'reason',
        'reference_number',
        'related_order_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity_change' => 'integer',
            'quantity_before' => 'integer',
            'quantity_after' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('movement_type', $type);
    }

    public static function recordMovement(
        Product $product,
        int $quantityChange,
        string $movementType,
        ?User $user = null,
        ?string $reason = null,
        ?Order $relatedOrder = null
    ): self {
        $movement = self::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'movement_type' => $movementType,
            'quantity_change' => $quantityChange,
            'quantity_before' => $product->stock_quantity,
            'quantity_after' => $product->stock_quantity + $quantityChange,
            'reason' => $reason,
            'related_order_id' => $relatedOrder?->id,
        ]);

        $product->increment('stock_quantity', $quantityChange);

        return $movement;
    }
}
