<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            PaymentMethodSeeder::class,
            TeamSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tuquiniela.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        User::factory(5)->create();
    }
}
