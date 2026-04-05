<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\RelationManagers;

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class PrizePositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'prizePositions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Prize Positions');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('position')
                ->label(__('Position'))
                ->numeric()
                ->required()
                ->minValue(1),
            TextInput::make('percentage')
                ->label(__('Percentage'))
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(100)
                ->suffix('%'),
        ]);
    }

    public function table(Table $table): Table
    {
        /** @var Quiniela $quiniela */
        $quiniela = $this->getOwnerRecord();
        $isCompleted = $quiniela->status === QuinielaStatus::Completed;

        return $table
            ->columns([
                TextColumn::make('position')
                    ->label(__('Position'))
                    ->sortable(),
                TextColumn::make('percentage')
                    ->label(__('Percentage'))
                    ->suffix('%')
                    ->sortable(),
            ])
            ->defaultSort('position')
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create prize position'))
                    ->modalHeading(__('Create prize position'))
                    ->visible(! $isCompleted),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('Edit prize position'))
                    ->modalHeading(__('Edit prize position'))
                    ->visible(! $isCompleted),
                DeleteAction::make()
                    ->visible(! $isCompleted),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(! $isCompleted),
                ]),
            ]);
    }
}
