<?php

declare(strict_types=1);

use App\Enums\TransactionStatus;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\User;

it('belongs to a user', function () {
    $deposit = Deposit::factory()->create();

    expect($deposit->user)->toBeInstanceOf(User::class);
});

it('belongs to a payment method', function () {
    $deposit = Deposit::factory()->create();

    expect($deposit->paymentMethod)->toBeInstanceOf(PaymentMethod::class);
});

it('casts status to enum', function () {
    $deposit = Deposit::factory()->create(['status' => TransactionStatus::Pending]);

    expect($deposit->status)->toBeInstanceOf(TransactionStatus::class);
});

it('can store proof of payment media', function () {
    $deposit = Deposit::factory()->create();

    $collections = collect($deposit->getRegisteredMediaCollections())->pluck('name');

    expect($collections)->toContain('proof');
});
