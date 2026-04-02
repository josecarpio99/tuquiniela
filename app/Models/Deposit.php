<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Database\Factories\DepositFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Deposit extends Model implements HasMedia
{
    /** @use HasFactory<DepositFactory> */
    use HasFactory, InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('proof')->singleFile();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
