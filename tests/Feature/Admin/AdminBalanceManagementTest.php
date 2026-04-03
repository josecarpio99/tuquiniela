<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\RelationManagers\WalletHistoryRelationManager;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can view a player balance', function () {
    $player = User::factory()->create();
    $player->depositFloat(50.00, ['reason' => 'Initial deposit']);

    Livewire::test(EditUser::class, ['record' => $player->getRouteKey()])
        ->assertSuccessful();
});

it('can view player transaction history', function () {
    $player = User::factory()->create();
    $player->depositFloat(25.00, ['reason' => 'Deposit']);
    $player->depositFloat(10.00, ['reason' => 'Bonus']);

    Livewire::test(WalletHistoryRelationManager::class, [
        'ownerRecord' => $player,
        'pageClass' => EditUser::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($player->walletHistory()->get());
});

it('can manually adjust player balance with a reason', function () {
    $player = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $player->getRouteKey()])
        ->callAction(TestAction::make('adjustBalance'), [
            'amount' => '50.00',
            'reason' => 'Manual credit by admin',
        ])
        ->assertNotified();

    expect((float) $player->fresh()->balanceFloat)->toBe(50.0);

    $transaction = $player->walletHistory()->latest()->first();
    expect($transaction->meta['reason'])->toBe('Manual credit by admin');
});
