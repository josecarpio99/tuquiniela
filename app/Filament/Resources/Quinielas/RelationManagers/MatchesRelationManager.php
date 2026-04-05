<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\RelationManagers;

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use App\Models\Team;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class MatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'matches';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Matches');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('team_1_id')
                ->label(__('Team 1'))
                ->options(Team::pluck('name', 'id'))
                ->searchable()
                ->required(),
            Select::make('team_2_id')
                ->label(__('Team 2'))
                ->options(Team::pluck('name', 'id'))
                ->searchable()
                ->required()
                ->different('team_1_id'),
            DateTimePicker::make('match_date')
                ->label(__('Match Date'))
                ->required(),
            TextInput::make('sort_order')
                ->label(__('Sort Order'))
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        /** @var Quiniela $quiniela */
        $quiniela = $this->getOwnerRecord();
        $isCompleted = $quiniela->status === QuinielaStatus::Completed;
        $isClosed = $quiniela->status === QuinielaStatus::Closed;

        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label(__('#'))
                    ->sortable(),
                TextColumn::make('team1.name')
                    ->label(__('Team 1')),
                TextColumn::make('team2.name')
                    ->label(__('Team 2')),
                TextColumn::make('match_date')
                    ->label(__('Match Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('team_1_score')
                    ->label(__('Score 1'))
                    ->placeholder('—'),
                TextColumn::make('team_2_score')
                    ->label(__('Score 2'))
                    ->placeholder('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->label(__('Create match'))
                    ->modalHeading(__('Create match'))
                    ->visible(! $isCompleted),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('Edit match'))
                    ->modalHeading(__('Edit match'))
                    ->visible(! $isCompleted),
                Action::make('enterResult')
                    ->label(__('Enter Result'))
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('warning')
                    ->visible($isClosed)
                    ->schema([
                        TextInput::make('team_1_score')
                            ->label(__('Team 1 Score'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('team_2_score')
                            ->label(__('Team 2 Score'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->fillForm(fn ($record): array => [
                        'team_1_score' => $record->team_1_score,
                        'team_2_score' => $record->team_2_score,
                    ])
                    ->action(function (array $data, $record): void {
                        $record->update([
                            'team_1_score' => $data['team_1_score'],
                            'team_2_score' => $data['team_2_score'],
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title(__('Result saved'))
                            ->success()
                            ->send();
                    }),
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
