<?php

declare(strict_types=1);

use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can render the teams list page', function () {
    $this->get(ListTeams::getUrl())->assertSuccessful();
});

it('can create a team', function () {
    Livewire::test(CreateTeam::class)
        ->fillForm([
            'name' => 'Real Madrid FC',
            'short_name' => 'RMA',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Team::class, [
        'name' => 'Real Madrid FC',
        'short_name' => 'RMA',
    ]);
});

it('can edit a team', function () {
    $team = Team::factory()->create(['name' => 'Old Name', 'short_name' => 'OLD']);

    Livewire::test(EditTeam::class, ['record' => $team->getRouteKey()])
        ->fillForm([
            'name' => 'New Name',
            'short_name' => 'NEW',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Team::class, [
        'id' => $team->id,
        'name' => 'New Name',
        'short_name' => 'NEW',
    ]);
});

it('can search teams by name', function () {
    $matchingTeam = Team::factory()->create(['name' => 'Barcelona FC']);
    $otherTeam = Team::factory()->create(['name' => 'Atletico Madrid']);

    Livewire::test(ListTeams::class)
        ->searchTable('Barcelona')
        ->assertCanSeeTableRecords(collect([$matchingTeam]))
        ->assertCanNotSeeTableRecords(collect([$otherTeam]));
});

it('can upload a team logo', function () {
    $team = Team::factory()->create();

    // Store a fake image to the public disk to simulate what FileUpload does
    $fakeImage = Illuminate\Http\UploadedFile::fake()->image('logo.png', 100, 100);
    $path = $fakeImage->storeAs('team-logos', 'test-logo.png', 'public');

    Livewire::test(EditTeam::class, ['record' => $team->getRouteKey()])
        ->fillForm([
            'logo' => [$path],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($team->fresh()->getMedia('logo'))->toHaveCount(1);
});
