<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('can render the users list page', function () {
    $this->get(ListUsers::getUrl())->assertSuccessful();
});

it('can list users in the table', function () {
    $users = User::factory()->count(3)->create();

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can search users by name', function () {
    $matchingUser = User::factory()->create(['name' => 'John Doe']);
    $otherUser = User::factory()->create(['name' => 'Jane Smith']);

    Livewire::test(ListUsers::class)
        ->searchTable('John')
        ->assertCanSeeTableRecords(collect([$matchingUser]))
        ->assertCanNotSeeTableRecords(collect([$otherUser]));
});

it('can search users by email', function () {
    $matchingUser = User::factory()->create(['email' => 'find-me@example.com']);
    $otherUser = User::factory()->create(['email' => 'other@example.com']);

    Livewire::test(ListUsers::class)
        ->searchTable('find-me')
        ->assertCanSeeTableRecords(collect([$matchingUser]))
        ->assertCanNotSeeTableRecords(collect([$otherUser]));
});

it('can create a new user', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New Player',
            'email' => 'newplayer@example.com',
            'password' => 'password123',
            'is_admin' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => 'New Player',
        'email' => 'newplayer@example.com',
        'is_admin' => false,
    ]);
});

it('can edit a user', function () {
    $user = User::factory()->create(['name' => 'Original Name', 'is_admin' => false]);

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => 'Updated Name',
            'is_admin' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'id' => $user->id,
        'name' => 'Updated Name',
        'is_admin' => true,
    ]);
});
