<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TransactionStatus;
use Database\Factories\WithdrawalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Withdrawal extends Model
{
    /** @use HasFactory<WithdrawalFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'amount',
        'status',
        'rejection_reason',
        'payment_details',
        'approved_at',
        'rejected_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class,
            'payment_details' => 'array',
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }
}
