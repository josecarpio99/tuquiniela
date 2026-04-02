<?php

use App\Models\QuinielaMatch;
use App\Models\Team;

it('can create a team', function () {
    $team = Team::factory()->create();

    $this->assertDatabaseHas('teams', ['id' => $team->id]);
});

it('has a logo media collection', function () {
    $team = Team::factory()->create();

    $names = collect($team->getRegisteredMediaCollections())->pluck('name');

    expect($names)->toContain('logo');
});

it('has home matches relationship', function () {
    $team = Team::factory()->create();
    QuinielaMatch::factory()->create(['team_1_id' => $team->id]);

    expect($team->homeMatches)->toHaveCount(1);
});

it('has away matches relationship', function () {
    $team = Team::factory()->create();
    QuinielaMatch::factory()->create(['team_2_id' => $team->id]);

    expect($team->awayMatches)->toHaveCount(1);
});
