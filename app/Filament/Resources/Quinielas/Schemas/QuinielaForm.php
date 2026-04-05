<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\Schemas;

use App\Enums\PredictionType;
use App\Enums\PrizeType;
use App\Enums\QuinielaStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

final class QuinielaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General'))
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Select::make('prediction_type')
                            ->label(__('Prediction Type'))
                            ->options(PredictionType::class)
                            ->required()
                            ->live(),
                        TextInput::make('ticket_cost')
                            ->label(__('Ticket Cost'))
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        DateTimePicker::make('closing_at')
                            ->label(__('Closing At'))
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(QuinielaStatus::class)
                            ->default(QuinielaStatus::Draft)
                            ->required(),
                    ]),
                Section::make(__('Points'))
                    ->columns(3)
                    ->components([
                        TextInput::make('points_correct_result')
                            ->label(__('Points Correct Result'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('points_exact_score')
                            ->label(__('Points Exact Score'))
                            ->numeric()
                            ->required()
                            ->default(4)
                            ->visible(fn (Get $get): bool => in_array($get('prediction_type'), [PredictionType::Score, PredictionType::Score->value], true)),
                        TextInput::make('points_wrong')
                            ->label(__('Points Wrong'))
                            ->numeric()
                            ->required()
                            ->default(-1)
                            ->visible(fn (Get $get): bool => in_array($get('prediction_type'), [PredictionType::Score, PredictionType::Score->value], true)),
                    ]),
                Section::make(__('Prize'))
                    ->columns(2)
                    ->components([
                        Select::make('prize_type')
                            ->label(__('Prize Type'))
                            ->options(PrizeType::class)
                            ->required()
                            ->live(),
                        TextInput::make('prize_pool_amount')
                            ->label(__('Prize Pool Amount'))
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (Get $get): bool => in_array($get('prize_type'), [PrizeType::Fixed, PrizeType::Fixed->value], true)),
                        TextInput::make('prize_pool_percentage')
                            ->label(__('Prize Pool Percentage'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->visible(fn (Get $get): bool => in_array($get('prize_type'), [PrizeType::Percentage, PrizeType::Percentage->value], true)),
                    ]),
            ]);
    }
}
