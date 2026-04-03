<?php

declare(strict_types=1);

use App\Enums\MatchResult;
use App\Models\Prediction;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use Illuminate\Database\QueryException;

it('belongs to a ticket', function () {
    $prediction = Prediction::factory()->create();

    expect($prediction->ticket)->toBeInstanceOf(Ticket::class);
});

it('belongs to a quiniela match', function () {
    $prediction = Prediction::factory()->create();

    expect($prediction->quinielaMatch)->toBeInstanceOf(QuinielaMatch::class);
});

it('casts predicted_result to enum', function () {
    $prediction = Prediction::factory()->create(['predicted_result' => MatchResult::Team1]);

    expect($prediction->predicted_result)->toBeInstanceOf(MatchResult::class);
});

it('enforces unique prediction per ticket per match', function () {
    $ticket = Ticket::factory()->create();
    $match = QuinielaMatch::factory()->create();

    Prediction::factory()->create(['ticket_id' => $ticket->id, 'quiniela_match_id' => $match->id]);

    expect(fn () => Prediction::factory()->create(['ticket_id' => $ticket->id, 'quiniela_match_id' => $match->id]))
        ->toThrow(QueryException::class);
});
