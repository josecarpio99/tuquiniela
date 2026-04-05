<?php

declare(strict_types=1);

use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Filament\Resources\Quinielas\RelationManagers\MatchesRelationManager;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Team;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can list matches for a quiniela', function () {
    $quiniela = Quiniela::factory()->create();
    $matches = QuinielaMatch::factory()->count(3)->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($matches);
});

it('can create a match for a quiniela', function () {
    $quiniela = Quiniela::factory()->create();
    $team1 = Team::factory()->create();
    $team2 = Team::factory()->create();

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'team_1_id' => $team1->id,
            'team_2_id' => $team2->id,
            'match_date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'sort_order' => 1,
        ])
        ->assertNotified();

    $this->assertDatabaseHas(QuinielaMatch::class, [
        'quiniela_id' => $quiniela->id,
        'team_1_id' => $team1->id,
        'team_2_id' => $team2->id,
    ]);
});

it('can edit a match', function () {
    $quiniela = Quiniela::factory()->create();
    $match = QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);
    $newTeam = Team::factory()->create();

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(Filament\Actions\EditAction::class)->table($match), [
            'team_1_id' => $newTeam->id,
        ])
        ->assertNotified();

    expect($match->fresh()->team_1_id)->toBe($newTeam->id);
});

it('can delete a match', function () {
    $quiniela = Quiniela::factory()->create();
    $match = QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make(Filament\Actions\DeleteAction::class)->table($match))
        ->assertNotified();

    $this->assertDatabaseMissing(QuinielaMatch::class, ['id' => $match->id]);
});

it('cannot modify matches on a completed quiniela', function () {
    $quiniela = Quiniela::factory()->completed()->create();
    $match = QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->assertActionHidden(TestAction::make(CreateAction::class)->table())
        ->assertActionHidden(TestAction::make(Filament\Actions\EditAction::class)->table($match))
        ->assertActionHidden(TestAction::make(Filament\Actions\DeleteAction::class)->table($match));
});
