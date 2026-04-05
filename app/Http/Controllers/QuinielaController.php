<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use Illuminate\View\View;

final class QuinielaController extends Controller
{
    public function index(): View
    {
        $openQuinielas = Quiniela::open()
            ->withCount('matches', 'tickets')
            ->with(['matches' => fn ($q) => $q->with('team1', 'team2')->orderBy('sort_order')->limit(3)])
            ->orderBy('closing_at')
            ->get();

        $completedQuinielas = Quiniela::completed()
            ->withCount('matches', 'tickets')
            ->latest('updated_at')
            ->take(10)
            ->get();

        $userTickets = collect();
        if ($user = auth()->user()) {
            $userTickets = $user->tickets()
                ->with('predictions')
                ->whereIn('quiniela_id', $openQuinielas->pluck('id'))
                ->get()
                ->groupBy('quiniela_id');
        }

        return view('quinielas.index', [
            'openQuinielas' => $openQuinielas,
            'completedQuinielas' => $completedQuinielas,
            'userTickets' => $userTickets,
        ]);
    }

    public function show(Quiniela $quiniela): View
    {
        abort_if($quiniela->status === QuinielaStatus::Draft, 404);

        $quiniela->load([
            'matches' => fn ($q) => $q->with('team1', 'team2')->orderBy('sort_order'),
            'prizePositions' => fn ($q) => $q->orderBy('position'),
        ]);
        $quiniela->loadCount('tickets');

        return view('quinielas.show', [
            'quiniela' => $quiniela,
        ]);
    }
}
