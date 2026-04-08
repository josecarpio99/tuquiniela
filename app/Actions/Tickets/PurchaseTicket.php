<?php

declare(strict_types=1);

namespace App\Actions\Tickets;

use App\Models\Quiniela;
use App\Models\Ticket;
use App\Models\User;

final class PurchaseTicket
{
    public function execute(User $user, Quiniela $quiniela): Ticket
    {
        abort_if(! $quiniela->isOpen(), 403, __('This quiniela is not open for ticket purchases.'));

        $user->withdrawFloat($quiniela->ticket_cost, [
            'reason' => __('Ticket purchase: :name', ['name' => $quiniela->name]),
            'quiniela_id' => $quiniela->id,
        ]);

        return $quiniela->tickets()->create([
            'user_id' => $user->id,
        ]);
    }
}
