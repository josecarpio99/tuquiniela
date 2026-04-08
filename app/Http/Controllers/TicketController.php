<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Quiniela;
use App\Models\Ticket;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = $request->user()
            ->tickets()
            ->with(['quiniela', 'predictions'])
            ->withCount('predictions')
            ->latest()
            ->paginate(15);

        return view('tickets.index', [
            'tickets' => $tickets,
        ]);
    }

    public function show(Request $request, Ticket $ticket): View
    {
        abort_if($ticket->user_id !== $request->user()->id, 403);

        $ticket->load([
            'quiniela',
            'predictions.quinielaMatch' => fn ($q) => $q->with('team1', 'team2'),
        ]);

        return view('tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    public function store(Request $request, Quiniela $quiniela): RedirectResponse
    {
        abort_if(! $quiniela->isOpen(), 403, __('This quiniela is not open for ticket purchases.'));

        $user = $request->user();

        try {
            $user->withdrawFloat($quiniela->ticket_cost, [
                'reason' => __('Ticket purchase: :name', ['name' => $quiniela->name]),
                'quiniela_id' => $quiniela->id,
            ]);
        } catch (InsufficientFunds|BalanceIsEmpty) {
            return back()->with('error', __('Insufficient balance to purchase this ticket. Please deposit funds first.'));
        }

        $ticket = $quiniela->tickets()->create([
            'user_id' => $user->id,
        ]);

        return redirect()->route('tickets.predictions', $ticket)
            ->with('success', __('Ticket purchased successfully! Now submit your predictions.'));
    }
}
