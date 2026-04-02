<?php

use App\Models\User;

it('has is_admin attribute', function () {
    $user = User::factory()->create();

    expect($user->is_admin)->toBeFalse();
});

it('can be created as admin', function () {
    $user = User::factory()->admin()->create();

    expect($user->is_admin)->toBeTrue();
});

it('has a wallet', function () {
    $user = User::factory()->create();

    expect($user->wallet)->not->toBeNull();
});

it('can deposit and withdraw from wallet', function () {
    $user = User::factory()->create();
    $user->deposit(1000);

    expect((int) $user->balance)->toBe(1000);

    $user->withdraw(500);

    expect((int) $user->balance)->toBe(500);
});

it('has an avatar media collection', function () {
    $user = User::factory()->create();

    $collections = $user->getRegisteredMediaCollections();
    $names = collect($collections)->pluck('name');

    expect($names)->toContain('avatar');
});
