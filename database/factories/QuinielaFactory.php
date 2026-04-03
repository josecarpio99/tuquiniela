<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PredictionType;
use App\Enums\PrizeType;
use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiniela>
 */
final class QuinielaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'prediction_type' => PredictionType::Result,
            'ticket_cost' => '1.00',
            'closing_at' => now()->addDays(7),
            'status' => QuinielaStatus::Draft,
            'points_correct_result' => 1,
            'points_exact_score' => 4,
            'points_wrong' => -1,
            'prize_type' => PrizeType::Fixed,
            'prize_pool_amount' => '100.00',
            'prize_pool_percentage' => null,
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuinielaStatus::Open,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuinielaStatus::Closed,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => QuinielaStatus::Completed,
        ]);
    }

    public function byScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'prediction_type' => PredictionType::Score,
        ]);
    }

    public function percentagePrize(): static
    {
        return $this->state(fn (array $attributes) => [
            'prize_type' => PrizeType::Percentage,
            'prize_pool_amount' => null,
            'prize_pool_percentage' => '60.00',
        ]);
    }
}
