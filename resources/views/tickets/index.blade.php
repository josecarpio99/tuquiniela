<x-layouts::app :title="__('My Tickets')">
    <div class="flex flex-col gap-6">
        {{-- Page header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold">{{ __('My Tickets') }}</h1>
            @if ($tickets->total() > 0)
                <div class="text-sm text-zinc-400">
                    <span class="text-white font-semibold">{{ $tickets->total() }}</span> {{ __('tickets total') }}
                </div>
            @endif
        </div>

        @if ($tickets->isEmpty())
            <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-8 text-center" data-test="empty-state">
                <div class="flex items-center justify-center h-12 w-12 mx-auto mb-4 rounded-xl bg-accent/10">
                    <svg class="h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">{{ __('No tickets yet') }}</h3>
                <p class="text-zinc-400 text-sm mb-4">{{ __('Browse available quinielas and purchase your first ticket.') }}</p>
                <a href="{{ route('quinielas.index') }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent/90 transition-colors">
                    {{ __('Browse Quinielas') }}
                </a>
            </div>
        @else
            <div class="space-y-4" data-test="tickets-list">
                @foreach ($tickets as $ticket)
                    @php
                        $quiniela = $ticket->quiniela;
                        $isActive = in_array($quiniela->status, [\App\Enums\QuinielaStatus::Open, \App\Enums\QuinielaStatus::Closed]);
                        $isCompleted = $quiniela->status === \App\Enums\QuinielaStatus::Completed;
                        $matchesCount = $quiniela->matches()->count();
                        $predictionsCount = $ticket->predictions_count;
                        $progressPercent = $matchesCount > 0 ? round(($predictionsCount / $matchesCount) * 100) : 0;
                        $allPredicted = $predictionsCount >= $matchesCount && $matchesCount > 0;
                        $pendingCount = $matchesCount - $predictionsCount;
                    @endphp
                    <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] hover:border-zinc-600 transition-colors" data-test="ticket-card">
                        <div class="p-5">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if ($isActive)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-accent pulse-dot"></span>
                                                {{ __('Active') }}
                                            </span>
                                        @elseif ($isCompleted)
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-zinc-500/10 text-zinc-400 border border-zinc-500/20">{{ __('Completed') }}</span>
                                        @endif
                                        <span @class([
                                            'inline-flex px-2 py-0.5 rounded text-xs font-medium border',
                                            'bg-blue-500/10 text-blue-400 border-blue-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Result,
                                            'bg-purple-500/10 text-purple-400 border-purple-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Score,
                                        ])>{{ $quiniela->prediction_type->getLabel() }}</span>
                                    </div>
                                    <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-lg font-semibold hover:text-accent transition-colors" data-test="ticket-quiniela-name">{{ $quiniela->name }}</a>
                                    <p class="text-sm text-zinc-400 mt-0.5">{{ __('Ticket #:id', ['id' => $ticket->id]) }} · {{ $ticket->created_at->diffForHumans() }}</p>
                                </div>
                                @if ($isActive && $quiniela->status === \App\Enums\QuinielaStatus::Open && $quiniela->closing_at)
                                    <div class="flex items-center gap-1.5 text-sm text-zinc-400 bg-[var(--color-base)] px-3 py-2 rounded-lg shrink-0">
                                        <svg class="h-4 w-4 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>{{ __('Closes') }} <strong class="text-yellow-400">{{ $quiniela->closing_at->diffForHumans() }}</strong></span>
                                    </div>
                                @endif
                            </div>

                            @if ($isActive)
                                {{-- Prediction progress --}}
                                <div class="mt-4 p-3 rounded-lg bg-[var(--color-base)]">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-zinc-300 font-medium">{{ __('Predictions') }}</span>
                                        <span class="text-sm text-accent font-semibold">{{ $predictionsCount }} / {{ $matchesCount }}</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-[var(--color-surface-hover)] overflow-hidden">
                                        <div class="h-full rounded-full bg-accent" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        @if ($allPredicted)
                                            <span class="text-xs text-accent">✓ {{ __('All completed') }}</span>
                                        @else
                                            <span class="text-xs text-yellow-400">⚠ {{ trans_choice(':count pending prediction|:count pending predictions', $pendingCount, ['count' => $pendingCount]) }}</span>
                                        @endif
                                        @if ($quiniela->status === \App\Enums\QuinielaStatus::Open)
                                            <a href="{{ route('tickets.predictions', $ticket) }}" wire:navigate class="text-xs text-accent font-semibold hover:text-accent/80 transition-colors">
                                                {{ $allPredicted ? __('View predictions') : __('Complete') }} →
                                            </a>
                                        @else
                                            <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-xs text-zinc-400 font-semibold hover:text-white transition-colors">{{ __('View predictions') }} →</a>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($isCompleted)
                                {{-- Results summary --}}
                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div class="text-center p-3 rounded-lg bg-[var(--color-base)]">
                                        <div class="text-xs text-zinc-500 mb-0.5">{{ __('Predictions') }}</div>
                                        <div class="text-lg font-bold text-zinc-300">{{ $predictionsCount }}/{{ $matchesCount }}</div>
                                    </div>
                                    <div class="text-center p-3 rounded-lg bg-[var(--color-base)]">
                                        <div class="text-xs text-zinc-500 mb-0.5">{{ __('Points') }}</div>
                                        <div class="text-lg font-bold">{{ $ticket->total_points ?? '—' }}</div>
                                    </div>
                                    <div class="text-center p-3 rounded-lg bg-[var(--color-base)]">
                                        <div class="text-xs text-zinc-500 mb-0.5">{{ __('Position') }}</div>
                                        <div class="text-lg font-bold text-zinc-400">—</div>
                                    </div>
                                    <div class="text-center p-3 rounded-lg bg-[var(--color-base)]">
                                        <div class="text-xs text-zinc-500 mb-0.5">{{ __('Prize') }}</div>
                                        <div class="text-lg font-bold {{ $ticket->prize_amount && (float) $ticket->prize_amount > 0 ? 'text-accent' : 'text-zinc-500' }}">
                                            {{ $ticket->prize_amount && (float) $ticket->prize_amount > 0 ? '$' . number_format((float) $ticket->prize_amount, 2) : '—' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
