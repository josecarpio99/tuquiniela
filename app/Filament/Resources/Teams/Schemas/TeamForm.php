<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('short_name')
                    ->label(__('Short Name'))
                    ->nullable()
                    ->maxLength(10),
                FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('team-logos')
                    ->visibility('public'),
            ]);
    }
}
