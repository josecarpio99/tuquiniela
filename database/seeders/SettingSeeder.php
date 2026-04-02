<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'min_deposit_amount' => '5.00',
            'max_deposit_amount' => '10000.00',
            'min_withdrawal_amount' => '5.00',
            'max_withdrawal_amount' => '10000.00',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
