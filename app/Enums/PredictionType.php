<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PredictionType: string implements HasColor, HasIcon, HasLabel
{
    case Result = 'result';
    case Score = 'score';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Result => __('enums.prediction_type.result'),
            self::Score => __('enums.prediction_type.score'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Result => 'primary',
            self::Score => 'success',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Result => Heroicon::Trophy,
            self::Score => Heroicon::Calculator,
        };
    }
}
