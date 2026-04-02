<?php

use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\Ticket;
use App\Models\User;

it('belongs to a quiniela', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->quiniela)->toBeInstanceOf(Quiniela::class);
});

it('belongs to a user', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->user)->toBeInstanceOf(User::class);
});

it('has predictions relationship', function () {
    $ticket = Ticket::factory()->create();
    Prediction::factory()->create(['ticket_id' => $ticket->id]);

    expect($ticket->predictions)->toHaveCount(1);
});
