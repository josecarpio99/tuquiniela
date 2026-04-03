<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PaymentMethodFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PaymentMethodField extends Model
{
    /** @use HasFactory<PaymentMethodFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'payment_method_id',
        'field_name',
        'field_label',
        'field_type',
        'is_required',
        'sort_order',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
        ];
    }
}
