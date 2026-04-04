<?php

declare(strict_types=1);

use App\Models\User;

test('welcome page shows TuQuiniela branding', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Tu<span class="text-accent">Quiniela</span>', false);
    $response->assertDontSee('Laravel Starter Kit');
});

test('welcome page uses dark theme class', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('class="dark"', false);
});

test('welcome page shows auth links for guests', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('login'));
    $response->assertSee(route('register'));
});

test('welcome page shows dashboard link for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('dashboard'));
});

test('dashboard shows TuQuiniela navbar with balance', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Tu<span class="text-accent">Quiniela</span>', false);
    $response->assertSee('data-test="navbar-balance"', false);
    $response->assertSee('data-test="balance-card"', false);
    $response->assertSee('data-test="dashboard-balance"', false);
});

test('dashboard shows personalized greeting', function () {
    $user = User::factory()->create(['name' => 'Carlos']);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Carlos');
});

test('auth pages use dark theme', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSee('class="dark"', false);
});
