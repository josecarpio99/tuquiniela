<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

final class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            ['name' => 'Real Madrid', 'short_name' => 'RMA'],
            ['name' => 'FC Barcelona', 'short_name' => 'FCB'],
            ['name' => 'Atlético de Madrid', 'short_name' => 'ATM'],
            ['name' => 'Manchester City', 'short_name' => 'MCI'],
            ['name' => 'Liverpool FC', 'short_name' => 'LIV'],
            ['name' => 'Arsenal FC', 'short_name' => 'ARS'],
            ['name' => 'Bayern Munich', 'short_name' => 'BAY'],
            ['name' => 'Borussia Dortmund', 'short_name' => 'BVB'],
            ['name' => 'Paris Saint-Germain', 'short_name' => 'PSG'],
            ['name' => 'Juventus', 'short_name' => 'JUV'],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
