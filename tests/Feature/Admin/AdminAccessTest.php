<?php

declare(strict_types=1);

use App\Models\User;

it('allows admin users to access the admin panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertSuccessful();
});

it('denies non-admin users access to the admin panel', function () {
    $player = User::factory()->create();

    $this->actingAs($player)
        ->get('/admin')
        ->assertForbidden();
});

it('redirects guests to login', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});
