<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Quiniela;
use App\Models\Team;
use Illuminate\Database\Seeder;

final class QuinielaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Team::count() < 8) {
            Team::factory(8)->create();
        }

        $teamIds = Team::query()
            ->inRandomOrder()
            ->limit(8)
            ->pluck('id')
            ->toArray();

        $quiniela = Quiniela::factory()
            ->open()
            ->create([
                'name' => 'Quiniela de prueba',
                'closing_at' => now()->addDay()->setTime(23, 59, 59),
            ]);

        $matches = [];
        foreach (array_chunk($teamIds, 2) as $sortOrder => $pair) {
            if (count($pair) !== 2) {
                continue;
            }

            $matches[] = [
                'team_1_id' => $pair[0],
                'team_2_id' => $pair[1],
                'match_date' => now()->addDay()->setTime(12 + ($sortOrder * 2), 0),
                'sort_order' => $sortOrder + 1,
            ];
        }

        $quiniela->matches()->createMany($matches);
    }
}
