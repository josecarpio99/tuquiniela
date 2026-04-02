<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum MatchResult: string implements HasColor, HasIcon, HasLabel
{
    case Team1 = 'team1';
    case Team2 = 'team2';
    case Draw = 'draw';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Team1 => 'Team 1 Wins',
            self::Team2 => 'Team 2 Wins',
            self::Draw => 'Draw',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Team1 => 'success',
            self::Team2 => 'danger',
            self::Draw => 'warning',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Team1 => Heroicon::ArrowLeft,
            self::Team2 => Heroicon::ArrowRight,
            self::Draw => Heroicon::Minus,
        };
    }
}
