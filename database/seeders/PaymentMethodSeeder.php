<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\PaymentMethodField;
use Illuminate\Database\Seeder;

final class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $binancePay = PaymentMethod::create([
            'name' => 'Binance Pay',
            'slug' => 'binance-pay',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        PaymentMethodField::create([
            'payment_method_id' => $binancePay->id,
            'field_name' => 'binance_id',
            'field_label' => 'Binance ID or Email',
            'field_type' => 'text',
            'is_required' => true,
            'sort_order' => 1,
        ]);
    }
}
