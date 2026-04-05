<x-layouts::app :title="__('Dashboard')">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ __('Hello, :name!', ['name' => auth()->user()->name]) }} 👋</h1>
        <p class="text-zinc-400">{{ __('Here is a summary of your activity') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4 mb-8">
        {{-- Balance --}}
        <div class="col-span-2 sm:col-span-1 rounded-xl border border-accent/20 bg-gradient-to-br from-accent/10 to-[var(--color-surface)] p-5" data-test="balance-card">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-3"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Available Balance') }}</span>
            </div>
            <div class="text-3xl font-bold text-accent" data-test="dashboard-balance">
                ${{ number_format((float) auth()->user()->balanceFloat, 2) }}
            </div>
            <a href="{{ route('balance.history') }}" wire:navigate class="inline-flex items-center gap-1 mt-2 text-xs text-accent hover:underline">
                {{ __('Deposit') }}
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        {{-- Active Tickets --}}
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5" data-test="active-tickets-card">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Active tickets') }}</span>
            </div>
            <div class="text-3xl font-bold" data-test="active-tickets-count">{{ $activeTicketsCount }}</div>
            <a href="{{ route('tickets.index') }}" wire:navigate class="inline-flex items-center gap-1 mt-2 text-xs text-zinc-500 hover:text-zinc-300 transition-colors">
                {{ __('View tickets') }}
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        {{-- Prizes Won --}}
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5" data-test="prizes-won-card">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Prizes won') }}</span>
            </div>
            <div class="text-3xl font-bold text-yellow-400" data-test="prizes-won">${{ number_format($prizesWon, 2) }}</div>
        </div>

        {{-- Quinielas Played --}}
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5" data-test="quinielas-played-card">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Quinielas played') }}</span>
            </div>
            <div class="text-3xl font-bold" data-test="quinielas-played">{{ $quinielasPlayed }}</div>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Active Quinielas (2/3 width) --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('Your Active Quinielas') }}</h2>
                <a href="{{ route('quinielas.index') }}" wire:navigate class="text-sm text-accent hover:text-accent/80 transition-colors">{{ __('View all') }} →</a>
            </div>

            @if ($activeTickets->isEmpty())
                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-8 text-center" data-test="no-active-quinielas">
                    <div class="flex items-center justify-center h-12 w-12 mx-auto mb-4 rounded-xl bg-accent/10">
                        <svg class="h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('No active quinielas yet') }}</h3>
                    <p class="text-zinc-400 text-sm mb-4">{{ __('When quinielas are available, they will appear here.') }}</p>
                    <a href="{{ route('quinielas.index') }}" wire:navigate
                       class="inline-flex items-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent/90 transition-colors">
                        {{ __('Browse Quinielas') }}
                    </a>
                </div>
            @else
                <div class="space-y-3" data-test="active-quinielas-list">
                    @foreach ($activeTickets as $ticket)
                        @php
                            $quiniela = $ticket->quiniela;
                            $predictionsCount = $ticket->predictions->count();
                            $matchesCount = $quiniela->matches()->count();
                            $progressPercent = $matchesCount > 0 ? round(($predictionsCount / $matchesCount) * 100) : 0;
                            $isOpen = $quiniela->status === \App\Enums\QuinielaStatus::Open;
                            $isClosed = $quiniela->status === \App\Enums\QuinielaStatus::Closed;
                            $matchesWithResults = $isClosed ? $quiniela->matches()->whereNotNull('team_1_score')->count() : 0;
                        @endphp
                        <a href="{{ route($isOpen ? 'tickets.predictions' : 'tickets.show', $ticket) }}" wire:navigate
                           class="block rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] hover:bg-[var(--color-surface-hover)] transition-colors p-4 {{ $isClosed ? 'opacity-80' : '' }}"
                           data-test="active-ticket-card">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        @if ($isOpen)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-accent pulse-dot"></span>
                                                {{ $quiniela->status->getLabel() }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                                {{ $quiniela->status->getLabel() }}
                                            </span>
                                        @endif
                                        <span @class([
                                            'inline-flex px-2 py-0.5 rounded text-xs font-medium border',
                                            'bg-blue-500/10 text-blue-400 border-blue-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Result,
                                            'bg-purple-500/10 text-purple-400 border-purple-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Score,
                                        ])>{{ $quiniela->prediction_type->getLabel() }}</span>
                                    </div>
                                    <h3 class="font-semibold">{{ $quiniela->name }}</h3>
                                </div>
                                <div class="text-right">
                                    @if ($isOpen)
                                        <div class="text-xs text-zinc-500">{{ __('Your ticket') }}</div>
                                        <div class="text-sm font-semibold {{ $predictionsCount >= $matchesCount ? 'text-accent' : 'text-white' }}">
                                            {{ $predictionsCount }}/{{ $matchesCount }} {{ __('predictions') }}
                                            @if ($predictionsCount >= $matchesCount) ✓ @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-zinc-500">{{ __('Waiting results') }}</div>
                                        <div class="text-sm font-semibold text-orange-400">{{ $matchesWithResults }}/{{ $matchesCount }} {{ __('results') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-4 text-zinc-400">
                                    <span>{{ $matchesCount }} {{ __('matches') }}</span>
                                    <span>•</span>
                                    @if ($isOpen)
                                        <span>{{ __('Prize') }}: <strong class="text-accent">${{ number_format((float) $quiniela->ticket_cost * 10, 2) }}</strong></span>
                                    @elseif ($ticket->total_points !== null)
                                        <span>{{ __('Partial points') }}: <strong class="text-white">{{ $ticket->total_points }}</strong></span>
                                    @endif
                                </div>
                                @if ($isOpen && $quiniela->closing_at)
                                    <div class="flex items-center gap-1.5 text-sm text-zinc-400">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>{{ __('Closes') }} <strong class="text-yellow-400">{{ $quiniela->closing_at->diffForHumans() }}</strong></span>
                                    </div>
                                @endif
                            </div>
                            {{-- Progress bar --}}
                            <div class="mt-3 h-1.5 rounded-full bg-[var(--color-base)] overflow-hidden">
                                <div class="h-full rounded-full {{ $isClosed ? 'bg-orange-400/60' : 'bg-accent' }}" style="width: {{ $isClosed ? ($matchesCount > 0 ? round(($matchesWithResults / $matchesCount) * 100) : 0) : $progressPercent }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div>
                <h2 class="text-lg font-semibold mb-4">{{ __('Quick actions') }}</h2>
                <div class="grid grid-cols-2 lg:grid-cols-1 gap-3">
                    <a href="{{ route('quinielas.index') }}" wire:navigate class="flex items-center gap-3 p-4 rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] hover:bg-[var(--color-surface-hover)] transition-colors">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium">{{ __('Play quiniela') }}</div>
                            <div class="text-xs text-zinc-500">{{ trans_choice(':count available|:count available', $openQuinielasCount, ['count' => $openQuinielasCount]) }}</div>
                        </div>
                    </a>
                    <a href="{{ route('balance.history') }}" wire:navigate class="flex items-center gap-3 p-4 rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] hover:bg-[var(--color-surface-hover)] transition-colors">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium">{{ __('Deposit funds') }}</div>
                            <div class="text-xs text-zinc-500">Binance Pay</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            @if ($recentTransactions->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold mb-4">{{ __('Recent activity') }}</h2>
                    <div class="space-y-1">
                        @foreach ($recentTransactions as $transaction)
                            @php
                                $isDeposit = $transaction->type === 'deposit';
                                $amount = (float) $transaction->amount / 100;
                                $meta = $transaction->meta ?? [];
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg">
                                <div @class([
                                    'flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center',
                                    'bg-accent/10' => $isDeposit,
                                    'bg-red-500/10' => !$isDeposit,
                                ])>
                                    @if ($isDeposit)
                                        <svg class="h-4 w-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                    @else
                                        <svg class="h-4 w-4 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium truncate">{{ $meta['reason'] ?? ($isDeposit ? __('Deposit') : __('Withdrawal')) }}</div>
                                    <div class="text-xs text-zinc-500">{{ $transaction->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-sm font-medium {{ $isDeposit ? 'text-accent' : 'text-red-400' }}">
                                    {{ $isDeposit ? '+' : '' }}${{ number_format(abs($amount), 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('balance.history') }}" wire:navigate class="block text-center mt-3 text-sm text-accent hover:text-accent/80 transition-colors">{{ __('View full history') }} →</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
