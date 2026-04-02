<?php

namespace App\Models;

use App\Enums\MatchResult;
use Database\Factories\QuinielaMatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuinielaMatch extends Model
{
    /** @use HasFactory<QuinielaMatchFactory> */
    use HasFactory;

    protected $fillable = [
        'quiniela_id',
        'team_1_id',
        'team_2_id',
        'match_date',
        'sort_order',
        'team_1_score',
        'team_2_score',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
            'team_1_score' => 'integer',
            'team_2_score' => 'integer',
        ];
    }

    public function quiniela(): BelongsTo
    {
        return $this->belongsTo(Quiniela::class);
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_2_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function hasResult(): bool
    {
        return $this->team_1_score !== null && $this->team_2_score !== null;
    }

    public function result(): ?MatchResult
    {
        if (! $this->hasResult()) {
            return null;
        }

        if ($this->team_1_score > $this->team_2_score) {
            return MatchResult::Team1;
        }

        if ($this->team_2_score > $this->team_1_score) {
            return MatchResult::Team2;
        }

        return MatchResult::Draw;
    }
}
