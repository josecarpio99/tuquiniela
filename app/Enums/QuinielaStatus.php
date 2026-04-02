<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum QuinielaStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
    case Completed = 'completed';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Draft => __('enums.quiniela_status.draft'),
            self::Open => __('enums.quiniela_status.open'),
            self::Closed => __('enums.quiniela_status.closed'),
            self::Completed => __('enums.quiniela_status.completed'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Open => 'success',
            self::Closed => 'warning',
            self::Completed => 'info',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Draft => Heroicon::PencilSquare,
            self::Open => Heroicon::LockOpen,
            self::Closed => Heroicon::LockClosed,
            self::Completed => Heroicon::CheckCircle,
        };
    }
}
