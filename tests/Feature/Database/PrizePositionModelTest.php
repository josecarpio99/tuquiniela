<?php

use App\Models\PrizePosition;
use App\Models\Quiniela;
use Illuminate\Database\QueryException;

it('belongs to a quiniela', function () {
    $prizePosition = PrizePosition::factory()->create();

    expect($prizePosition->quiniela)->toBeInstanceOf(Quiniela::class);
});

it('enforces unique position per quiniela', function () {
    $quiniela = Quiniela::factory()->create();

    PrizePosition::factory()->create(['quiniela_id' => $quiniela->id, 'position' => 1]);

    expect(fn () => PrizePosition::factory()->create(['quiniela_id' => $quiniela->id, 'position' => 1]))
        ->toThrow(QueryException::class);
});
