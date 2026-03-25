<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_number',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'transaction_reference',
        'payment_details',
        'failure_reason',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_details' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public static function generatePaymentNumber(): string
    {
        $prefix = 'PAY-'.date('Ymd');
        $lastPayment = self::where('payment_number', 'like', "{$prefix}%")
            ->orderBy('payment_number', 'desc')
            ->first();

        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->payment_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix.str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
