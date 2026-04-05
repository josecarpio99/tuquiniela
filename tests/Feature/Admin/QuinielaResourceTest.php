<?php

declare(strict_types=1);

use App\Enums\PredictionType;
use App\Enums\PrizeType;
use App\Enums\QuinielaStatus;
use App\Filament\Resources\Quinielas\Pages\CreateQuiniela;
use App\Filament\Resources\Quinielas\Pages\EditQuiniela;
use App\Filament\Resources\Quinielas\Pages\ListQuinielas;
use App\Models\Quiniela;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can render the quinielas list page', function () {
    $this->get(ListQuinielas::getUrl())->assertSuccessful();
});

it('can list quinielas in the table', function () {
    $quinielas = Quiniela::factory()->count(3)->create();

    Livewire::test(ListQuinielas::class)
        ->assertCanSeeTableRecords($quinielas);
});

it('can search quinielas by name', function () {
    $matching = Quiniela::factory()->create(['name' => 'Liga MX Jornada 1']);
    $other = Quiniela::factory()->create(['name' => 'Premier League']);

    Livewire::test(ListQuinielas::class)
        ->searchTable('Liga MX')
        ->assertCanSeeTableRecords(collect([$matching]))
        ->assertCanNotSeeTableRecords(collect([$other]));
});

it('can filter quinielas by status', function () {
    $draft = Quiniela::factory()->create(['status' => QuinielaStatus::Draft]);
    $open = Quiniela::factory()->open()->create();

    Livewire::test(ListQuinielas::class)
        ->filterTable('status', QuinielaStatus::Draft->value)
        ->assertCanSeeTableRecords(collect([$draft]))
        ->assertCanNotSeeTableRecords(collect([$open]));
});

it('can create a quiniela', function () {
    Livewire::test(CreateQuiniela::class)
        ->fillForm([
            'name' => 'Test Quiniela',
            'prediction_type' => PredictionType::Result->value,
            'ticket_cost' => '5.00',
            'closing_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'status' => QuinielaStatus::Draft->value,
            'points_correct_result' => 1,
            'prize_type' => PrizeType::Fixed->value,
            'prize_pool_amount' => '100.00',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Quiniela::class, [
        'name' => 'Test Quiniela',
        'prediction_type' => PredictionType::Result->value,
        'ticket_cost' => '5.00',
    ]);
});

it('can edit a quiniela', function () {
    $quiniela = Quiniela::factory()->create(['name' => 'Original']);

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Quiniela::class, [
        'id' => $quiniela->id,
        'name' => 'Updated Name',
    ]);
});

it('can delete a draft quiniela', function () {
    $quiniela = Quiniela::factory()->create();

    Livewire::test(EditQuiniela::class, ['record' => $quiniela->getRouteKey()])
        ->callAction(Filament\Actions\DeleteAction::class);

    $this->assertDatabaseMissing(Quiniela::class, ['id' => $quiniela->id]);
});
