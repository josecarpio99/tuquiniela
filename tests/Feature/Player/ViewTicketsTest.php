<?php

declare(strict_types=1);

use App\Enums\MatchResult;
use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;

it('displays the tickets list page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('tickets.index'));

    $response->assertOk();
});

it('shows only the authenticated player tickets', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $quiniela = Quiniela::factory()->open()->create();

    $myTicket = Ticket::factory()->create(['user_id' => $user->id, 'quiniela_id' => $quiniela->id]);
    $otherTicket = Ticket::factory()->create(['user_id' => $otherUser->id, 'quiniela_id' => $quiniela->id]);

    $this->actingAs($user);

    $response = $this->get(route('tickets.index'));

    $response->assertOk();
    $response->assertSeeText($quiniela->name);
});

it('shows ticket detail with predictions', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->open()->create();
    $match = QuinielaMatch::factory()->for($quiniela)->create();
    $ticket = Ticket::factory()->create(['user_id' => $user->id, 'quiniela_id' => $quiniela->id]);
    Prediction::factory()->create([
        'ticket_id' => $ticket->id,
        'quiniela_match_id' => $match->id,
        'predicted_result' => MatchResult::Team1,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('tickets.show', $ticket));

    $response->assertOk();
    $response->assertSeeText($match->team1->name);
    $response->assertSeeText($match->team2->name);
});

it('requires authentication', function () {
    $this->get(route('tickets.index'))
        ->assertRedirect(route('login'));

    $ticket = Ticket::factory()->create();

    $this->get(route('tickets.show', $ticket))
        ->assertRedirect(route('login'));
});

it('cannot view another users ticket detail', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = Ticket::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    $this->get(route('tickets.show', $ticket))
        ->assertForbidden();
});
