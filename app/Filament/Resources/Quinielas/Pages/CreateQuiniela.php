<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\Pages;

use App\Filament\Resources\Quinielas\QuinielaResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateQuiniela extends CreateRecord
{
    protected static string $resource = QuinielaResource::class;
}
