<?php

declare(strict_types=1);

use App\Models\User;

it('creates a wallet for new users', function () {
    $user = User::factory()->create();

    // Wallet is lazily created on first balance access
    expect($user->wallet)->not->toBeNull();
    expect($user->balanceFloat)->not->toBeNull();
});

it('starts with zero balance', function () {
    $user = User::factory()->create();

    expect((float) $user->balanceFloat)->toBe(0.0);
});
