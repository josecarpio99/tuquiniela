<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class QuinielasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prediction_type')
                    ->label(__('Prediction Type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('ticket_cost')
                    ->label(__('Ticket Cost'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('tickets_count')
                    ->label(__('Tickets Sold'))
                    ->counts('tickets')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('closing_at')
                    ->label(__('Closing At'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('prize_type')
                    ->label(__('Prize Type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(\App\Enums\QuinielaStatus::class),
                SelectFilter::make('prediction_type')
                    ->label(__('Prediction Type'))
                    ->options(\App\Enums\PredictionType::class),
                SelectFilter::make('prize_type')
                    ->label(__('Prize Type'))
                    ->options(\App\Enums\PrizeType::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
