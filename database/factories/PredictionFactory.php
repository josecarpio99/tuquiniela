<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MatchResult;
use App\Models\Prediction;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prediction>
 */
final class PredictionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'quiniela_match_id' => QuinielaMatch::factory(),
            'predicted_result' => fake()->randomElement(MatchResult::cases()),
            'predicted_team_1_score' => null,
            'predicted_team_2_score' => null,
        ];
    }

    public function byScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'predicted_result' => null,
            'predicted_team_1_score' => fake()->numberBetween(0, 5),
            'predicted_team_2_score' => fake()->numberBetween(0, 5),
        ]);
    }
}
