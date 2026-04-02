<?php

namespace Database\Factories;

use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuinielaMatch>
 */
class QuinielaMatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiniela_id' => Quiniela::factory(),
            'team_1_id' => Team::factory(),
            'team_2_id' => Team::factory(),
            'match_date' => now()->addDays(fake()->numberBetween(1, 30)),
            'sort_order' => 0,
            'team_1_score' => null,
            'team_2_score' => null,
        ];
    }

    public function withResult(): static
    {
        return $this->state(fn (array $attributes) => [
            'team_1_score' => fake()->numberBetween(0, 5),
            'team_2_score' => fake()->numberBetween(0, 5),
        ]);
    }
}
