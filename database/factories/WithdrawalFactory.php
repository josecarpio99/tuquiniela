<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Withdrawal>
 */
final class WithdrawalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => fake()->randomFloat(2, 5, 50),
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
