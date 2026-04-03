<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $isCreate = $schema->getLivewire() instanceof \App\Filament\Resources\Users\Pages\CreateUser;

        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->revealable()
                    ->rule(Password::defaults())
                    ->required($isCreate)
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->visibleOn('create'),
                Toggle::make('is_admin')
                    ->label(__('Admin')),
            ]);
    }
}
