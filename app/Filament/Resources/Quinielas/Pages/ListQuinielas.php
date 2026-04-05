<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\Pages;

use App\Filament\Resources\Quinielas\QuinielaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListQuinielas extends ListRecords
{
    protected static string $resource = QuinielaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
