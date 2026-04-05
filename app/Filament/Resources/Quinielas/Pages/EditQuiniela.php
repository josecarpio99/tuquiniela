<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quinielas\Pages;

use App\Enums\QuinielaStatus;
use App\Filament\Resources\Quinielas\QuinielaResource;
use App\Models\Quiniela;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

final class EditQuiniela extends EditRecord
{
    protected static string $resource = QuinielaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open')
                ->label(__('Open'))
                ->icon(Heroicon::OutlinedLockOpen)
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->getRecord()->status === QuinielaStatus::Draft)
                ->action(function (): void {
                    /** @var Quiniela $quiniela */
                    $quiniela = $this->getRecord();

                    if ($quiniela->matches()->count() === 0) {
                        Notification::make()
                            ->title(__('Cannot open quiniela without matches'))
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($quiniela->prizePositions()->count() === 0) {
                        Notification::make()
                            ->title(__('Cannot open quiniela without prize positions'))
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($quiniela->closing_at->isPast()) {
                        Notification::make()
                            ->title(__('Cannot open quiniela with past closing date'))
                            ->danger()
                            ->send();

                        return;
                    }

                    $quiniela->update(['status' => QuinielaStatus::Open]);

                    Notification::make()
                        ->title(__('Quiniela opened successfully'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
            Action::make('close')
                ->label(__('Close'))
                ->icon(Heroicon::OutlinedLockClosed)
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->getRecord()->status === QuinielaStatus::Open)
                ->action(function (): void {
                    /** @var Quiniela $quiniela */
                    $quiniela = $this->getRecord();

                    $quiniela->update(['status' => QuinielaStatus::Closed]);

                    Notification::make()
                        ->title(__('Quiniela closed successfully'))
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),
            DeleteAction::make(),
        ];
    }
}
