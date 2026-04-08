<?php

declare(strict_types=1);

namespace App\Actions\Tickets;

use App\Enums\PredictionType;
use App\Models\Ticket;
use App\Models\User;

final class SubmitPredictions
{
    /**
     * Persist predictions for a ticket.
     *
     * Expected format for $predictionsData:
     * - Result mode: [match_id => ['predicted_result' => 'team1'|'team2'|'draw']]
     * - Score mode:  [match_id => ['team_1_score' => int, 'team_2_score' => int]]
     *
     * Callers are responsible for validating input before invoking this action.
     *
     * @param  array<int, array{predicted_result?: string|null, team_1_score?: int|null, team_2_score?: int|null}>  $predictionsData
     */
    public function execute(User $user, Ticket $ticket, array $predictionsData): void
    {
        abort_if($ticket->user_id !== $user->id, 403);

        $ticket->loadMissing(['quiniela.matches']);

        abort_if(! $ticket->quiniela->isOpen(), 403);

        $isScoreMode = $ticket->quiniela->prediction_type === PredictionType::Score;

        foreach ($ticket->quiniela->matches as $match) {
            $matchData = $predictionsData[$match->id] ?? [];

            if ($isScoreMode) {
                $data = [
                    'ticket_id' => $ticket->id,
                    'quiniela_match_id' => $match->id,
                    'predicted_team_1_score' => (int) ($matchData['team_1_score'] ?? 0),
                    'predicted_team_2_score' => (int) ($matchData['team_2_score'] ?? 0),
                    'predicted_result' => null,
                ];
            } else {
                $data = [
                    'ticket_id' => $ticket->id,
                    'quiniela_match_id' => $match->id,
                    'predicted_result' => $matchData['predicted_result'] ?? null,
                    'predicted_team_1_score' => null,
                    'predicted_team_2_score' => null,
                ];
            }

            $ticket->predictions()->updateOrCreate(
                ['quiniela_match_id' => $match->id],
                $data,
            );
        }
    }
}
