<?php

declare(strict_types=1);

use App\Enums\TransactionStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Withdrawal;

it('belongs to a user', function () {
    $withdrawal = Withdrawal::factory()->create();

    expect($withdrawal->user)->toBeInstanceOf(User::class);
});

it('belongs to a payment method', function () {
    $withdrawal = Withdrawal::factory()->create();

    expect($withdrawal->paymentMethod)->toBeInstanceOf(PaymentMethod::class);
});

it('casts status to enum', function () {
    $withdrawal = Withdrawal::factory()->create(['status' => TransactionStatus::Pending]);

    expect($withdrawal->status)->toBeInstanceOf(TransactionStatus::class);
});
