<?php

declare(strict_types=1);

use App\Models\User;

it('displays the player balance on the dashboard', function () {
    $user = User::factory()->create();
    $user->depositFloat(150.75, ['reason' => 'Test deposit']);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk();
    $response->assertSeeText('150.75');
});
