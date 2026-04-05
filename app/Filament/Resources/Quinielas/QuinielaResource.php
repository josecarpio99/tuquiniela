<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas;

use App\Filament\Resources\Quinielas\Pages\CreateQuiniela;
use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Filament\Resources\Quinielas\Pages\ListQuinielas;
use App\Filament\Resources\Quinielas\RelationManagers\MatchesRelationManager;
use App\Filament\Resources\Quinielas\RelationManagers\PrizePositionsRelationManager;
use App\Filament\Resources\Quinielas\Schemas\QuinielaForm;
use App\Filament\Resources\Quinielas\Tables\QuinielasTable;
use App\Models\Quiniela;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class QuinielaResource extends Resource
{
    protected static ?string $model = Quiniela::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function getModelLabel(): string
    {
        return __('Quiniela');
    }

    public static function form(Schema $schema): Schema
    {
        return QuinielaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuinielasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MatchesRelationManager::class,
            PrizePositionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuinielas::route('/'),
            'create' => CreateQuiniela::route('/create'),
            'edit' => EditQuiniela::route('/{record}/edit'),
        ];
    }
}
