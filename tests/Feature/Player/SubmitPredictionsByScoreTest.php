<?php

declare(strict_types=1);

use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->quiniela = Quiniela::factory()->open()->byScore()->create();
    $this->matches = QuinielaMatch::factory()
        ->for($this->quiniela)
        ->count(3)
        ->create();
    $this->ticket = Ticket::factory()->create([
        'quiniela_id' => $this->quiniela->id,
        'user_id' => $this->user->id,
    ]);
});

it('can render the score prediction form', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('tickets.predictions', $this->ticket));

    $response->assertOk();
});

it('can submit score predictions for all matches', function () {
    $this->actingAs($this->user);

    $scores1 = [];
    $scores2 = [];
    foreach ($this->matches as $match) {
        $scores1[$match->id] = fake()->numberBetween(0, 5);
        $scores2[$match->id] = fake()->numberBetween(0, 5);
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('scores1', $scores1)
        ->set('scores2', $scores2)
        ->call('submit')
        ->assertRedirect(route('tickets.show', $this->ticket));

    foreach ($this->matches as $match) {
        $prediction = Prediction::where('ticket_id', $this->ticket->id)
            ->where('quiniela_match_id', $match->id)
            ->first();

        expect($prediction)->not()->toBeNull();
        expect($prediction->predicted_team_1_score)->toBe($scores1[$match->id]);
        expect($prediction->predicted_team_2_score)->toBe($scores2[$match->id]);
    }
});

it('validates scores are non-negative integers', function () {
    $this->actingAs($this->user);

    $scores1 = [];
    $scores2 = [];
    foreach ($this->matches as $match) {
        $scores1[$match->id] = -1;
        $scores2[$match->id] = -1;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('scores1', $scores1)
        ->set('scores2', $scores2)
        ->call('submit')
        ->assertHasErrors();
});

it('requires predictions for all matches', function () {
    $this->actingAs($this->user);

    $scores1 = [];
    $scores2 = [];
    $scores1[$this->matches->first()->id] = 1;
    $scores2[$this->matches->first()->id] = 0;

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('scores1', $scores1)
        ->set('scores2', $scores2)
        ->call('submit')
        ->assertHasErrors();
});
