<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Quiniela;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
final class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiniela_id' => Quiniela::factory(),
            'user_id' => User::factory(),
        ];
    }
}
