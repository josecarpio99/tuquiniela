<?php

namespace Database\Factories;

use App\Models\PrizePosition;
use App\Models\Quiniela;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrizePosition>
 */
class PrizePositionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiniela_id' => Quiniela::factory(),
            'position' => 1,
            'percentage' => '100.00',
        ];
    }
}
