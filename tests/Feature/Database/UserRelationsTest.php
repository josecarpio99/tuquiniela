<?php

declare(strict_types=1);

use App\Models\Deposit;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Withdrawal;

it('has tickets relationship', function () {
    $user = User::factory()->create();
    Ticket::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->tickets)->toHaveCount(2);
});

it('has deposits relationship', function () {
    $user = User::factory()->create();
    Deposit::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->deposits)->toHaveCount(3);
});

it('has withdrawals relationship', function () {
    $user = User::factory()->create();
    Withdrawal::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->withdrawals)->toHaveCount(2);
});
