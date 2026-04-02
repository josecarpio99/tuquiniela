<?php

namespace App\Models;

use App\Enums\PredictionType;
use App\Enums\PrizeType;
use App\Enums\QuinielaStatus;
use Database\Factories\QuinielaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiniela extends Model
{
    /** @use HasFactory<QuinielaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'prediction_type',
        'ticket_cost',
        'closing_at',
        'status',
        'points_correct_result',
        'points_exact_score',
        'points_wrong',
        'prize_type',
        'prize_pool_amount',
        'prize_pool_percentage',
    ];

    protected function casts(): array
    {
        return [
            'prediction_type' => PredictionType::class,
            'status' => QuinielaStatus::class,
            'prize_type' => PrizeType::class,
            'closing_at' => 'datetime',
            'ticket_cost' => 'decimal:2',
        ];
    }

    public function matches(): HasMany
    {
        return $this->hasMany(QuinielaMatch::class);
    }

    public function prizePositions(): HasMany
    {
        return $this->hasMany(PrizePosition::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', QuinielaStatus::Draft);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', QuinielaStatus::Open);
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', QuinielaStatus::Closed);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', QuinielaStatus::Completed);
    }
}
