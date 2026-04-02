<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->city().' FC';
        $short = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));

        return [
            'name' => $name,
            'short_name' => $short,
        ];
    }
}
