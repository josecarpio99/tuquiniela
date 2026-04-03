<?php

declare(strict_types=1);

use App\Models\User;

it('displays the balance history page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('balance.history'));

    $response->assertOk();
    $response->assertSeeText(__('Transaction History'));
});

it('shows transaction entries', function () {
    $user = User::factory()->create();
    $user->depositFloat(50.00, ['reason' => 'Test deposit']);
    $user->withdrawFloat(10.00, ['reason' => 'Test withdrawal']);

    $this->actingAs($user);

    $response = $this->get(route('balance.history'));

    $response->assertOk();
    $response->assertSeeText('Test deposit');
    $response->assertSeeText('Test withdrawal');
    $response->assertSeeText('50.00');
    $response->assertSeeText('10.00');
});

it('requires authentication', function () {
    $response = $this->get(route('balance.history'));

    $response->assertRedirect(route('login'));
});
