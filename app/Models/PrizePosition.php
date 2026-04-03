<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PrizePositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PrizePosition extends Model
{
    /** @use HasFactory<PrizePositionFactory> */
    use HasFactory;

    protected $fillable = ['quiniela_id', 'position', 'percentage'];

    public function quiniela(): BelongsTo
    {
        return $this->belongsTo(Quiniela::class);
    }
}
