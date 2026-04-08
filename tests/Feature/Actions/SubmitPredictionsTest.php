<?php

declare(strict_types=1);

use App\Actions\Tickets\SubmitPredictions;
use App\Enums\MatchResult;
use App\Enums\QuinielaStatus;
use App\Models\Prediction;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->quiniela = Quiniela::factory()->open()->create();
    $this->matches = QuinielaMatch::factory()->for($this->quiniela)->count(3)->create();
    $this->ticket = Ticket::factory()->create([
        'quiniela_id' => $this->quiniela->id,
        'user_id' => $this->user->id,
    ]);
});

it('saves result predictions for all matches', function () {
    $predictionsData = [];
    foreach ($this->matches as $match) {
        $predictionsData[$match->id] = ['predicted_result' => MatchResult::Team1->value];
    }

    (new SubmitPredictions)->execute($this->user, $this->ticket, $predictionsData);

    foreach ($this->matches as $match) {
        $prediction = Prediction::where('ticket_id', $this->ticket->id)
            ->where('quiniela_match_id', $match->id)
            ->first();

        expect($prediction)->not()->toBeNull();
        expect($prediction->predicted_result)->toBe(MatchResult::Team1);
        expect($prediction->predicted_team_1_score)->toBeNull();
        expect($prediction->predicted_team_2_score)->toBeNull();
    }
});

it('saves score predictions for all matches', function () {
    $quiniela = Quiniela::factory()->open()->byScore()->create();
    $matches = QuinielaMatch::factory()->for($quiniela)->count(3)->create();
    $ticket = Ticket::factory()->create([
        'quiniela_id' => $quiniela->id,
        'user_id' => $this->user->id,
    ]);

    $predictionsData = [];
    foreach ($matches as $match) {
        $predictionsData[$match->id] = ['team_1_score' => 2, 'team_2_score' => 1];
    }

    (new SubmitPredictions)->execute($this->user, $ticket, $predictionsData);

    foreach ($matches as $match) {
        $prediction = Prediction::where('ticket_id', $ticket->id)
            ->where('quiniela_match_id', $match->id)
            ->first();

        expect($prediction)->not()->toBeNull();
        expect($prediction->predicted_team_1_score)->toBe(2);
        expect($prediction->predicted_team_2_score)->toBe(1);
        expect($prediction->predicted_result)->toBeNull();
    }
});

it('updates existing predictions', function () {
    $match = $this->matches->first();

    Prediction::factory()->create([
        'ticket_id' => $this->ticket->id,
        'quiniela_match_id' => $match->id,
        'predicted_result' => MatchResult::Team1,
    ]);

    $predictionsData = [$match->id => ['predicted_result' => MatchResult::Draw->value]];

    (new SubmitPredictions)->execute($this->user, $this->ticket, $predictionsData);

    expect(Prediction::where('ticket_id', $this->ticket->id)
        ->where('quiniela_match_id', $match->id)
        ->first()->predicted_result)->toBe(MatchResult::Draw);
});

it('throws 403 when ticket belongs to a different user', function () {
    $otherUser = User::factory()->create();
    $predictionsData = [$this->matches->first()->id => ['predicted_result' => MatchResult::Team1->value]];

    (new SubmitPredictions)->execute($otherUser, $this->ticket, $predictionsData);
})->throws(HttpException::class);

it('throws 403 when quiniela is closed', function () {
    $this->quiniela->update(['status' => QuinielaStatus::Closed]);
    $predictionsData = [$this->matches->first()->id => ['predicted_result' => MatchResult::Team1->value]];

    (new SubmitPredictions)->execute($this->user, $this->ticket, $predictionsData);
})->throws(HttpException::class);

it('throws 403 when quiniela has passed its closing date', function () {
    $this->quiniela->update(['closing_at' => now()->subMinute()]);
    $predictionsData = [$this->matches->first()->id => ['predicted_result' => MatchResult::Team1->value]];

    (new SubmitPredictions)->execute($this->user, $this->ticket, $predictionsData);
})->throws(HttpException::class);
