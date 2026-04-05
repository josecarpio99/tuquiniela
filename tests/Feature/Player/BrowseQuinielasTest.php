<?php

declare(strict_types=1);

use App\Models\Quiniela;
use App\Models\QuinielaMatch;

it('displays open quinielas', function () {
    $quiniela = Quiniela::factory()->open()->create();
    QuinielaMatch::factory()->for($quiniela)->create();

    $response = $this->get(route('quinielas.index'));

    $response->assertOk();
    $response->assertSeeText($quiniela->name);
});

it('does not display draft quinielas', function () {
    $quiniela = Quiniela::factory()->create();

    $response = $this->get(route('quinielas.index'));

    $response->assertOk();
    $response->assertDontSeeText($quiniela->name);
});

it('displays completed quinielas in separate section', function () {
    $completed = Quiniela::factory()->completed()->create();

    $response = $this->get(route('quinielas.index'));

    $response->assertOk();
    $response->assertSeeText($completed->name);
});

it('shows quiniela detail page', function () {
    $quiniela = Quiniela::factory()->open()->create();
    $match = QuinielaMatch::factory()->for($quiniela)->create();

    $response = $this->get(route('quinielas.show', $quiniela));

    $response->assertOk();
    $response->assertSeeText($quiniela->name);
    $response->assertSeeText($match->team1->name);
    $response->assertSeeText($match->team2->name);
});

it('is accessible to guests', function () {
    $quiniela = Quiniela::factory()->open()->create();

    $this->get(route('quinielas.index'))->assertOk();
    $this->get(route('quinielas.show', $quiniela))->assertOk();
});
