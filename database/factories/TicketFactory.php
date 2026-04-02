<?php

namespace Database\Factories;

use App\Models\Quiniela;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiniela_id' => Quiniela::factory(),
            'user_id' => User::factory(),
        ];
    }
}
