<?php

declare(strict_types=1);

use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard shows active tickets count', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->open()->create();
    QuinielaMatch::factory()->for($quiniela)->create();
    Ticket::factory()->create(['user_id' => $user->id, 'quiniela_id' => $quiniela->id]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('activeTicketsCount', 1);
});

test('dashboard shows quinielas played count', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->completed()->create();
    $ticket = Ticket::factory()->create(['user_id' => $user->id, 'quiniela_id' => $quiniela->id]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('quinielasPlayed', 1);
});

test('dashboard shows prizes won total', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->completed()->create();
    Ticket::factory()->create([
        'user_id' => $user->id,
        'quiniela_id' => $quiniela->id,
        'prize_amount' => 50.00,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('prizesWon', 50.00);
});

test('dashboard shows open quinielas count', function () {
    $user = User::factory()->create();
    Quiniela::factory()->open()->count(3)->create();

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertViewHas('openQuinielasCount', 3);
});
