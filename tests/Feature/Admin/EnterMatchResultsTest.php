<?php

declare(strict_types=1);

use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Filament\Resources\Quinielas\RelationManagers\MatchesRelationManager;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can enter match results for a closed quiniela', function () {
    $quiniela = Quiniela::factory()->closed()->create();
    $match = QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make('enterResult')->table($match), [
            'team_1_score' => 2,
            'team_2_score' => 1,
        ])
        ->assertNotified();

    $match->refresh();
    expect($match->team_1_score)->toBe(2);
    expect($match->team_2_score)->toBe(1);
});

it('cannot enter results for non-closed quiniela', function () {
    $quiniela = Quiniela::factory()->create(['status' => App\Enums\QuinielaStatus::Draft]);
    $match = QuinielaMatch::factory()->create(['quiniela_id' => $quiniela->id]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->assertActionHidden(TestAction::make('enterResult')->table($match));
});

it('can edit previously entered results', function () {
    $quiniela = Quiniela::factory()->closed()->create();
    $match = QuinielaMatch::factory()->withResult()->create([
        'quiniela_id' => $quiniela->id,
        'team_1_score' => 1,
        'team_2_score' => 0,
    ]);

    Livewire::test(MatchesRelationManager::class, [
        'ownerRecord' => $quiniela,
        'pageClass' => EditQuiniela::class,
    ])
        ->callAction(TestAction::make('enterResult')->table($match), [
            'team_1_score' => 3,
            'team_2_score' => 2,
        ])
        ->assertNotified();

    $match->refresh();
    expect($match->team_1_score)->toBe(3);
    expect($match->team_2_score)->toBe(2);
});
