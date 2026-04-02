<?php

use App\Enums\MatchResult;
use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Team;

it('belongs to a quiniela', function () {
    $match = QuinielaMatch::factory()->create();

    expect($match->quiniela)->toBeInstanceOf(Quiniela::class);
});

it('belongs to team 1', function () {
    $match = QuinielaMatch::factory()->create();

    expect($match->team1)->toBeInstanceOf(Team::class);
});

it('belongs to team 2', function () {
    $match = QuinielaMatch::factory()->create();

    expect($match->team2)->toBeInstanceOf(Team::class);
});

it('can determine match result', function () {
    $matchTeam1Wins = QuinielaMatch::factory()->create(['team_1_score' => 2, 'team_2_score' => 0]);
    $matchTeam2Wins = QuinielaMatch::factory()->create(['team_1_score' => 0, 'team_2_score' => 3]);
    $matchDraw = QuinielaMatch::factory()->create(['team_1_score' => 1, 'team_2_score' => 1]);

    expect($matchTeam1Wins->result())->toBe(MatchResult::Team1);
    expect($matchTeam2Wins->result())->toBe(MatchResult::Team2);
    expect($matchDraw->result())->toBe(MatchResult::Draw);
});

it('knows if result has been entered', function () {
    $matchWithResult = QuinielaMatch::factory()->create(['team_1_score' => 1, 'team_2_score' => 0]);
    $matchWithoutResult = QuinielaMatch::factory()->create();

    expect($matchWithResult->hasResult())->toBeTrue();
    expect($matchWithoutResult->hasResult())->toBeFalse();
});

it('has predictions relationship', function () {
    $match = QuinielaMatch::factory()->create();
    Prediction::factory()->create(['quiniela_match_id' => $match->id]);

    expect($match->predictions)->toHaveCount(1);
});
