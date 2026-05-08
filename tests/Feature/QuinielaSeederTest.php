<?php

declare(strict_types=1);

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use App\Models\Team;
use Database\Seeders\QuinielaSeeder;

it('seeds an open quiniela with a closing date of tomorrow and associated matches', function (): void {
    Team::factory(8)->create();

    $this->seed(QuinielaSeeder::class);

    $quiniela = Quiniela::query()->latest('id')->first();

    expect($quiniela)->not->toBeNull();
    expect($quiniela->status)->toBe(QuinielaStatus::Open);
    expect($quiniela->closing_at)->not->toBeNull();
    expect($quiniela->closing_at->isSameDay(now()->addDay()))->toBeTrue();
    expect($quiniela->matches()->count())->toBe(4);
});
