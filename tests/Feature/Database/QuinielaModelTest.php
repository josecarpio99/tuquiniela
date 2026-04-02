<?php

use App\Enums\PredictionType;
use App\Enums\QuinielaStatus;
use App\Models\PrizePosition;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;

it('can create a quiniela', function () {
    $quiniela = Quiniela::factory()->create();

    $this->assertDatabaseHas('quinielas', ['id' => $quiniela->id]);
});

it('casts prediction_type to enum', function () {
    $quiniela = Quiniela::factory()->create(['prediction_type' => PredictionType::Result]);

    expect($quiniela->prediction_type)->toBeInstanceOf(PredictionType::class);
});

it('casts status to enum', function () {
    $quiniela = Quiniela::factory()->create(['status' => QuinielaStatus::Open]);

    expect($quiniela->status)->toBeInstanceOf(QuinielaStatus::class);
});

it('has matches relationship', function () {
    $quiniela = Quiniela::factory()->create();
    QuinielaMatch::factory()->count(3)->create(['quiniela_id' => $quiniela->id]);

    expect($quiniela->matches)->toHaveCount(3);
});

it('has prize_positions relationship', function () {
    $quiniela = Quiniela::factory()->create();
    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id, 'position' => 1]);
    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id, 'position' => 2]);

    expect($quiniela->prizePositions)->toHaveCount(2);
});

it('has tickets relationship', function () {
    $quiniela = Quiniela::factory()->create();
    Ticket::factory()->count(2)->create(['quiniela_id' => $quiniela->id]);

    expect($quiniela->tickets)->toHaveCount(2);
});

it('can scope by status', function () {
    Quiniela::factory()->open()->create();
    Quiniela::factory()->closed()->create();
    Quiniela::factory()->completed()->create();
    Quiniela::factory()->create(); // draft

    expect(Quiniela::open()->count())->toBe(1);
    expect(Quiniela::closed()->count())->toBe(1);
    expect(Quiniela::completed()->count())->toBe(1);
    expect(Quiniela::draft()->count())->toBe(1);
});
