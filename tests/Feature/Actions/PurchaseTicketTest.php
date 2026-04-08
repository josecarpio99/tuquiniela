<?php

declare(strict_types=1);

use App\Actions\Tickets\PurchaseTicket;
use App\Models\Quiniela;
use App\Models\QuinielaMatch;
use App\Models\Ticket;
use App\Models\User;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('creates a ticket and deducts wallet balance', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);
    QuinielaMatch::factory()->for($quiniela)->create();

    $ticket = (new PurchaseTicket)->execute($user, $quiniela);

    expect($ticket)->toBeInstanceOf(Ticket::class);
    expect($ticket->user_id)->toBe($user->id);
    expect($ticket->quiniela_id)->toBe($quiniela->id);
    expect((float) $user->fresh()->balanceFloat)->toBe(90.00);
});

it('throws 403 when quiniela is not open', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->closed()->create();

    (new PurchaseTicket)->execute($user, $quiniela);
})->throws(HttpException::class);

it('throws 403 when quiniela has passed its closing date', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->open()->create([
        'closing_at' => now()->subMinute(),
        'ticket_cost' => '10.00',
    ]);

    (new PurchaseTicket)->execute($user, $quiniela);
})->throws(HttpException::class);

it('lets wallet exception bubble up when user has no balance', function () {
    $user = User::factory()->create();
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);

    (new PurchaseTicket)->execute($user, $quiniela);
})->throws(BalanceIsEmpty::class);

it('stores wallet transaction metadata', function () {
    $user = User::factory()->create();
    $user->depositFloat(50.00, ['reason' => 'Test deposit']);
    $quiniela = Quiniela::factory()->open()->create(['ticket_cost' => '10.00']);
    QuinielaMatch::factory()->for($quiniela)->create();

    (new PurchaseTicket)->execute($user, $quiniela);

    $transaction = $user->walletHistory()->latest('id')->first();
    expect($transaction->meta['quiniela_id'])->toBe($quiniela->id);
});
