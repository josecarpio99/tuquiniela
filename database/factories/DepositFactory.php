<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deposit>
 */
class DepositFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => fake()->randomFloat(2, 5, 100),
            'status' => TransactionStatus::Pending,
            'rejection_reason' => null,
            'payment_details' => null,
            'approved_at' => null,
            'rejected_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::Approved,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TransactionStatus::Rejected,
            'rejected_at' => now(),
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
