<?php

declare(strict_types=1);

use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Filament\Resources\Quinielas\RelationManagers\PrizePositionsRelationManager;
use App\Models\PrizePosition;
use App\Models\Quiniela;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can list prize positions for a quiniela', function () {
    $quiniela = Quiniela::factory()->create();
    $positions = PrizePosition::factory()->count(2)->sequence(
        ['quiniela_id' => $quiniela->id, 'position' => 1, 'percentage' => '70.00'],
        ['quiniela_id' => $quiniela->id, 'position' => 2, 'percentage' => '30.00'],
    )->create();

    Livewire::test(PrizePositionsRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($positions);
});

it('can create a prize position', function () {
    $quiniela = Quiniela::factory()->create();

    Livewire::test(PrizePositionsRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'position' => 1,
            'percentage' => '100.00',
        ])
        ->assertNotified();

    $this->assertDatabaseHas(PrizePosition::class, [
        'quiniela_id' => $quiniela->id,
        'position' => 1,
        'percentage' => '100.00',
    ]);
});

it('can edit a prize position', function () {
    $quiniela = Quiniela::factory()->create();
    $position = PrizePosition::factory()->create([
        'quiniela_id' => $quiniela->id,
        'position' => 1,
        'percentage' => '100.00',
    ]);

    Livewire::test(PrizePositionsRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(Filament\Actions\EditAction::class)->table($position), [
            'percentage' => '80.00',
        ])
        ->assertNotified();

    expect((float) $position->fresh()->percentage)->toBe(80.0);
});

it('can delete a prize position', function () {
    $quiniela = Quiniela::factory()->create();
    $position = PrizePosition::factory()->create([
        'quiniela_id' => $quiniela->id,
    ]);

    Livewire::test(PrizePositionsRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(Filament\Actions\DeleteAction::class)->table($position))
        ->assertNotified();

    $this->assertDatabaseMissing(PrizePosition::class, ['id' => $position->id]);
});
