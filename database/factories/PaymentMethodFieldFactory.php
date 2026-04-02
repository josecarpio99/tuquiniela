<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\PaymentMethodField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentMethodField>
 */
class PaymentMethodFieldFactory extends Factory
{
    public function definition(): array
    {
        $fieldName = fake()->word();

        return [
            'payment_method_id' => PaymentMethod::factory(),
            'field_name' => $fieldName,
            'field_label' => ucfirst($fieldName),
            'field_type' => 'text',
            'is_required' => true,
            'sort_order' => 0,
        ];
    }
}
