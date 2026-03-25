<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'purity',
        'price_per_gram',
        'price_per_ounce',
        'price_per_tola',
        'currency',
        'source',
        'is_active',
        'effective_from',
        'effective_until',
    ];

    protected function casts(): array
    {
        return [
            'price_per_gram' => 'decimal:2',
            'price_per_ounce' => 'decimal:2',
            'price_per_tola' => 'decimal:2',
            'is_active' => 'boolean',
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('effective_until')
                    ->orWhere('effective_until', '>=', now());
            });
    }

    public function scopeByPurity($query, string $purity)
    {
        return $query->where('purity', $purity);
    }

    public static function getCurrentPrice(string $purity): ?self
    {
        return self::current()->byPurity($purity)->first();
    }
}
