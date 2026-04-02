<?php

namespace App\Models;

use App\Enums\MatchResult;
use Database\Factories\PredictionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    /** @use HasFactory<PredictionFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'quiniela_match_id',
        'predicted_result',
        'predicted_team_1_score',
        'predicted_team_2_score',
        'points_earned',
    ];

    protected function casts(): array
    {
        return [
            'predicted_result' => MatchResult::class,
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function quinielaMatch(): BelongsTo
    {
        return $this->belongsTo(QuinielaMatch::class);
    }
}
