<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PrizeType: string implements HasColor, HasIcon, HasLabel
{
    case Fixed = 'fixed';
    case Percentage = 'percentage';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Fixed => __('enums.prize_type.fixed'),
            self::Percentage => __('enums.prize_type.percentage'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Fixed => 'warning',
            self::Percentage => 'info',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Fixed => Heroicon::Banknotes,
            self::Percentage => Heroicon::ChartPie,
        };
    }
}
