<?php

declare(strict_types=1);

use App\Enums\MatchResult;
use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->quiniela = Quiniela::factory()->open()->create();
    $this->matches = QuinielaMatch::factory()
        ->for($this->quiniela)
        ->count(3)
        ->create();
    $this->ticket = Ticket::factory()->create([
        'quiniela_id' => $this->quiniela->id,
        'user_id' => $this->user->id,
    ]);
});

it('can render the prediction form', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('tickets.predictions', $this->ticket));

    $response->assertOk();
    foreach ($this->matches as $match) {
        $response->assertSeeText($match->team1->name);
        $response->assertSeeText($match->team2->name);
    }
});

it('can submit predictions for all matches', function () {
    $this->actingAs($this->user);

    $predictions = [];
    foreach ($this->matches as $match) {
        $predictions[$match->id] = fake()->randomElement(MatchResult::cases())->value;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertRedirect(route('tickets.show', $this->ticket));

    foreach ($this->matches as $match) {
        expect(Prediction::where('ticket_id', $this->ticket->id)
            ->where('quiniela_match_id', $match->id)
            ->exists())->toBeTrue();
    }
});

it('requires predictions for all matches', function () {
    $this->actingAs($this->user);

    $predictions = [];
    $predictions[$this->matches->first()->id] = MatchResult::Team1->value;

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertHasErrors();
});

it('can only predict on own tickets', function () {
    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);

    $this->get(route('tickets.predictions', $this->ticket))
        ->assertForbidden();
});

it('cannot submit predictions for closed quiniela', function () {
    $this->quiniela->update(['status' => App\Enums\QuinielaStatus::Closed]);
    $this->actingAs($this->user);

    $predictions = [];
    foreach ($this->matches as $match) {
        $predictions[$match->id] = MatchResult::Team1->value;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertForbidden();
});
