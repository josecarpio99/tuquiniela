<?php

declare(strict_types=1);

use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Withdrawal;

it('has fields relationship', function () {
    $method = PaymentMethod::factory()->hasFields(2)->create();

    expect($method->fields)->toHaveCount(2);
});

it('can scope active methods', function () {
    PaymentMethod::factory()->create(['is_active' => true]);
    PaymentMethod::factory()->create(['is_active' => false]);

    expect(PaymentMethod::active()->count())->toBe(1);
});

it('has deposits relationship', function () {
    $method = PaymentMethod::factory()->create();
    Deposit::factory()->create(['payment_method_id' => $method->id]);

    expect($method->deposits)->toHaveCount(1);
});

it('has withdrawals relationship', function () {
    $method = PaymentMethod::factory()->create();
    Withdrawal::factory()->create(['payment_method_id' => $method->id]);

    expect($method->withdrawals)->toHaveCount(1);
});
