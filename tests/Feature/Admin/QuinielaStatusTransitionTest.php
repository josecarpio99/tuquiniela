<?php

declare(strict_types=1);

use App\Enums\QuinielaStatus;
use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Models\PrizePosition;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can open a draft quiniela with matches and prizes', function () {
    $quiniela = Quiniela::factory()->create([
        'status' => QuinielaStatus::Draft,
        'closing_at' => now()->addDays(7),
    ]);
    QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);
    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction('open')
        ->assertNotified();

    expect($quiniela->fresh()->status)->toBe(QuinielaStatus::Open);
});

it('cannot open a quiniela without matches', function () {
    $quiniela = Quiniela::factory()->create([
        'status' => QuinielaStatus::Draft,
        'closing_at' => now()->addDays(7),
    ]);
    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction('open')
        ->assertNotified();

    expect($quiniela->fresh()->status)->toBe(QuinielaStatus::Draft);
});

it('cannot open a quiniela without prize positions', function () {
    $quiniela = Quiniela::factory()->create([
        'status' => QuinielaStatus::Draft,
        'closing_at' => now()->addDays(7),
    ]);
    QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction('open')
        ->assertNotified();

    expect($quiniela->fresh()->status)->toBe(QuinielaStatus::Draft);
});

it('cannot open a quiniela with past closing date', function () {
    $quiniela = Quiniela::factory()->create([
        'status' => QuinielaStatus::Draft,
        'closing_at' => now()->subDay(),
    ]);
    QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);
    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction('open')
        ->assertNotified();

    expect($quiniela->fresh()->status)->toBe(QuinielaStatus::Draft);
});

it('can close an open quiniela', function () {
    $quiniela = Quiniela::factory()->open()->create();

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction('close')
        ->assertNotified();

    expect($quiniela->fresh()->status)->toBe(QuinielaStatus::Closed);
});

it('hides open action when quiniela is not draft', function () {
    $quiniela = Quiniela::factory()->open()->create();

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->assertActionHidden('open');
});

it('hides close action when quiniela is not open', function () {
    $quiniela = Quiniela::factory()->create(['status' => QuinielaStatus::Draft]);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->assertActionHidden('close');
});
