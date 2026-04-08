<?php

declare(strict_types=1);

use App\Enums\MatchResult;
use App\Enums\QuinielaStatus;
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

    foreach ($this->matches as $match) {
        Prediction::factory()->create([
            'ticket_id' => $this->ticket->id,
            'quiniela_match_id' => $match->id,
            'predicted_result' => MatchResult::Team1,
        ]);
    }
});

it('can edit existing predictions while quiniela is open', function () {
    $this->actingAs($this->user);

    $predictions = [];
    foreach ($this->matches as $match) {
        $predictions[$match->id] = MatchResult::Draw->value;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertRedirect(route('tickets.show', $this->ticket));

    foreach ($this->matches as $match) {
        $prediction = Prediction::where('ticket_id', $this->ticket->id)
            ->where('quiniela_match_id', $match->id)
            ->first();

        expect($prediction->predicted_result)->toBe(MatchResult::Draw);
    }
});

it('cannot edit predictions after quiniela closes', function () {
    $this->quiniela->update(['status' => QuinielaStatus::Closed]);
    $this->actingAs($this->user);

    $predictions = [];
    foreach ($this->matches as $match) {
        $predictions[$match->id] = MatchResult::Team2->value;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertForbidden();
});

it('cannot edit predictions after closing date passes', function () {
    $this->quiniela->update([
        'status' => QuinielaStatus::Open,
        'closing_at' => now()->subMinute(),
    ]);
    $this->actingAs($this->user);

    $predictions = [];
    foreach ($this->matches as $match) {
        $predictions[$match->id] = MatchResult::Team2->value;
    }

    Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket])
        ->set('predictions', $predictions)
        ->call('submit')
        ->assertForbidden();
});

it('pre-fills existing predictions', function () {
    $this->actingAs($this->user);

    $component = Livewire::test('pages::tickets.predictions', ['ticket' => $this->ticket]);

    foreach ($this->matches as $match) {
        $component->assertSet("predictions.{$match->id}", MatchResult::Team1->value);
    }
});
