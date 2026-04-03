<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class BalanceController extends Controller
{
    public function history(Request $request): View
    {
        $transactions = $request->user()
            ->walletHistory()
            ->latest()
            ->paginate(15);

        return view('balance.history', [
            'transactions' => $transactions,
        ]);
    }
}
