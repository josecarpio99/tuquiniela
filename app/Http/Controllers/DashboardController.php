<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $activeTickets = $user->tickets()
            ->with(['quiniela', 'predictions'])
            ->whereHas('quiniela', fn ($q) => $q->whereIn('status', [QuinielaStatus::Open, QuinielaStatus::Closed]))
            ->latest()
            ->get();

        $activeTicketsCount = $activeTickets->count();

        $prizesWon = (float) $user->tickets()
            ->whereNotNull('prize_amount')
            ->where('prize_amount', '>', 0)
            ->sum('prize_amount');

        $quinielasPlayed = $user->tickets()->distinct('quiniela_id')->count('quiniela_id');

        $recentTransactions = $user->walletHistory()
            ->latest()
            ->take(5)
            ->get();

        $openQuinielasCount = Quiniela::open()->count();

        return view('dashboard', [
            'activeTickets' => $activeTickets,
            'activeTicketsCount' => $activeTicketsCount,
            'prizesWon' => $prizesWon,
            'quinielasPlayed' => $quinielasPlayed,
            'recentTransactions' => $recentTransactions,
            'openQuinielasCount' => $openQuinielasCount,
        ]);
    }
}
