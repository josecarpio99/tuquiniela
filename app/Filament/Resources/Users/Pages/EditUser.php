<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('adjustBalance')
                ->label(__('Adjust Balance'))
                ->icon(\Filament\Support\Icons\Heroicon::OutlinedCurrencyDollar)
                ->color('warning')
                ->schema([
                    TextInput::make('amount')
                        ->label(__('Amount (positive to add, negative to deduct)'))
                        ->numeric()
                        ->required(),
                    Textarea::make('reason')
                        ->label(__('Reason'))
                        ->required()
                        ->rows(2),
                ])
                ->action(function (array $data): void {
                    /** @var User $user */
                    $user = $this->getRecord();
                    $amount = (float) $data['amount'];
                    $meta = ['reason' => $data['reason']];

                    if ($amount >= 0) {
                        $user->depositFloat($amount, $meta);
                    } else {
                        $user->withdrawFloat(abs($amount), $meta);
                    }

                    Notification::make()
                        ->title(__('Balance adjusted successfully'))
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
