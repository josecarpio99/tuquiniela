<?php

declare(strict_types=1);

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;

it('auto-closes quinielas past closing date', function () {
    $expired = Quiniela::factory()->open()->create([
        'closing_at' => now()->subHour(),
    ]);

    $this->artisan('quiniela:close-expired')
        ->assertSuccessful();

    expect($expired->fresh()->status)->toBe(QuinielaStatus::Closed);
});

it('does not close quinielas with future closing date', function () {
    $future = Quiniela::factory()->open()->create([
        'closing_at' => now()->addDays(3),
    ]);

    $this->artisan('quiniela:close-expired')
        ->assertSuccessful();

    expect($future->fresh()->status)->toBe(QuinielaStatus::Open);
});

it('does not close draft quinielas', function () {
    $draft = Quiniela::factory()->create([
        'status' => QuinielaStatus::Draft,
        'closing_at' => now()->subHour(),
    ]);

    $this->artisan('quiniela:close-expired')
        ->assertSuccessful();

    expect($draft->fresh()->status)->toBe(QuinielaStatus::Draft);
});
