<?php

declare(strict_types=1);

use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;

it('can purchase a ticket for an open quiniela', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);
    QuinielaMatch::factory()->for($quiniela)->create();

    $this->actingAs($user);

    $response = $this->post(route('tickets.store', $quiniela));

    $response->assertRedirect();
    expect(Ticket::where('user_id', $user->id)->where('quiniela_id', $quiniela->id)->exists())->toBeTrue();
    expect((float) $user->fresh()->balanceFloat)->toBe(90.00);
});

it('cannot purchase a ticket without sufficient balance', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);

    $this->actingAs($user);

    $response = $this->post(route('tickets.store', $quiniela));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    expect(Ticket::where('user_id', $user->id)->exists())->toBeFalse();
});

it('cannot purchase a ticket for a closed quiniela', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->closed()->create();

    $this->actingAs($user);

    $response = $this->post(route('tickets.store', $quiniela));

    $response->assertForbidden();
});

it('can purchase multiple tickets for the same quiniela', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '5.00']);

    $this->actingAs($user);

    $this->post(route('tickets.store', $quiniela));
    $this->post(route('tickets.store', $quiniela));

    expect(Ticket::where('user_id', $user->id)->where('quiniela_id', $quiniela->id)->count())->toBe(2);
    expect((float) $user->fresh()->balanceFloat)->toBe(90.00);
});

it('requires authentication', function () {
    $quiniela = Quiniela::factory()->open()->create();

    $response = $this->post(route('tickets.store', $quiniela));

    $response->assertRedirect(route('login'));
});

it('records wallet transaction with quiniela reference', function () {
    $user = User::factory()->create();
    $user->depositFloat(50.00, ['reason' => 'Initial deposit']);
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);

    $this->actingAs($user);

    $this->post(route('tickets.store', $quiniela));

    $transaction = $user->walletHistory()->latest('id')->first();
    expect($transaction->meta['quiniela_id'])->toBe($quiniela->id);
});
