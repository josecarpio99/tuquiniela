<x-layouts::app :title="__('Quinielas')">
    <div class="flex flex-col gap-8">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ __('Quinielas') }}</h1>
            <p class="text-zinc-400">{{ __('Browse available quinielas and join the action.') }}</p>
        </div>

        {{-- Open Quinielas --}}
        <section>
            @if ($openQuinielas->isEmpty())
                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-8 text-center" data-test="no-open-quinielas">
                    <div class="flex items-center justify-center h-12 w-12 mx-auto mb-4 rounded-xl bg-accent/10">
                        <svg class="h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('No open quinielas') }}</h3>
                    <p class="text-zinc-400 text-sm">{{ __('Check back soon for new quinielas.') }}</p>
                </div>
            @else
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-zinc-500">{{ trans_choice(':count open quiniela|:count open quinielas', $openQuinielas->count(), ['count' => $openQuinielas->count()]) }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-test="open-quinielas">
                    @foreach ($openQuinielas as $quiniela)
                        @php
                            $closingSoon = $quiniela->closing_at && $quiniela->closing_at->diffInHours(now()) < 24;
                            $tickets = isset($userTickets) ? ($userTickets[$quiniela->id] ?? collect()) : collect();
                            $hasTicket = $tickets->isNotEmpty();
                        @endphp
                        <a href="{{ route('quinielas.show', $quiniela) }}" wire:navigate
                           class="card-hover rounded-xl border {{ $closingSoon ? 'border-yellow-500/30' : 'border-[var(--color-border-default)]' }} bg-[var(--color-surface)] p-5 flex flex-col"
                           data-test="quiniela-card">
                            {{-- Status badges --}}
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        @if ($closingSoon)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-400 pulse-dot"></span>
                                                {{ __('Closing soon!') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-accent pulse-dot"></span>
                                                {{ $quiniela->status->getLabel() }}
                                            </span>
                                        @endif
                                        <span @class([
                                            'inline-flex px-2 py-0.5 rounded text-xs font-medium border',
                                            'bg-blue-500/10 text-blue-400 border-blue-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Result,
                                            'bg-purple-500/10 text-purple-400 border-purple-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Score,
                                        ])>{{ $quiniela->prediction_type->getLabel() }}</span>
                                    </div>
                                    <h3 class="text-lg font-semibold mt-2" data-test="quiniela-name">{{ $quiniela->name }}</h3>
                                </div>
                            </div>

                            {{-- Match previews --}}
                            @if ($quiniela->matches->isNotEmpty())
                                <div class="space-y-2 mb-4">
                                    @foreach ($quiniela->matches->take(3) as $match)
                                        <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                            <span class="text-zinc-300 truncate">{{ $match->team1->name }}</span>
                                            <span class="text-zinc-500 px-2 shrink-0">vs</span>
                                            <span class="text-zinc-300 truncate text-right">{{ $match->team2->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Stats --}}
                            <div class="mt-auto">
                                <div class="flex items-center justify-between text-sm mb-4 pb-4 border-b border-[var(--color-border-default)]">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <span class="text-zinc-500">{{ __('Cost') }}</span>
                                            <div class="font-semibold text-white">${{ number_format((float) $quiniela->ticket_cost, 2) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-zinc-500">{{ __('Matches') }}</span>
                                            <div class="font-semibold text-white">{{ $quiniela->matches_count }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5 text-sm text-zinc-400">
                                        <svg class="h-4 w-4 {{ $closingSoon ? 'text-yellow-400' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>{{ __('Closes') }} <strong class="{{ $closingSoon ? 'text-red-400' : 'text-yellow-400' }}">{{ $quiniela->closing_at->diffForHumans() }}</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-sm text-zinc-400">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span>{{ $quiniela->tickets_count }}</span>
                                    </div>
                                </div>

                                {{-- Ticket indicator or buy CTA --}}
                                @auth
                                    @if ($hasTicket)
                                        @php
                                            $ticket = $tickets->first();
                                            $predictionsCount = $ticket->predictions->count();
                                        @endphp
                                        <div class="mt-3 flex items-center gap-2 px-3 py-2 rounded-lg bg-accent/10 border border-accent/20 text-sm">
                                            <svg class="h-4 w-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                                            <span class="text-accent font-medium">{{ trans_choice('You have :count ticket|You have :count tickets', $tickets->count(), ['count' => $tickets->count()]) }} — {{ $predictionsCount }}/{{ $quiniela->matches_count }} {{ __('predictions') }}</span>
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <span class="block w-full text-center px-4 py-2.5 rounded-lg {{ $closingSoon ? 'bg-yellow-500 hover:bg-yellow-600 text-black' : 'bg-accent hover:bg-accent/80 text-white' }} text-sm font-semibold transition-colors">
                                                {{ $closingSoon ? __('Last hours!') . ' — ' : __('Buy ticket') . ' — ' }}${{ number_format((float) $quiniela->ticket_cost, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-3">
                                        <span class="block w-full text-center px-4 py-2.5 rounded-lg bg-accent hover:bg-accent/80 text-white text-sm font-semibold transition-colors">
                                            {{ __('Buy ticket') }} — ${{ number_format((float) $quiniela->ticket_cost, 2) }}
                                        </span>
                                    </div>
                                @endauth
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Completed Quinielas --}}
        @if ($completedQuinielas->isNotEmpty())
            <section>
                <h2 class="text-lg font-semibold mb-4">{{ __('Recently completed') }}</h2>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-test="completed-quinielas">
                    @foreach ($completedQuinielas as $quiniela)
                        <a href="{{ route('quinielas.show', $quiniela) }}" wire:navigate
                           class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5 opacity-70 hover:opacity-100 transition-all">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-zinc-500/10 text-zinc-400 border border-zinc-500/20">{{ $quiniela->status->getLabel() }}</span>
                                <span @class([
                                    'inline-flex px-2 py-0.5 rounded text-xs font-medium border',
                                    'bg-blue-500/10 text-blue-400 border-blue-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Result,
                                    'bg-purple-500/10 text-purple-400 border-purple-500/20' => $quiniela->prediction_type === \App\Enums\PredictionType::Score,
                                ])>{{ $quiniela->prediction_type->getLabel() }}</span>
                            </div>
                            <h3 class="font-semibold mb-3">{{ $quiniela->name }}</h3>
                            <div class="flex items-center justify-between text-sm mb-3 pb-3 border-b border-[var(--color-border-default)]">
                                <span class="text-zinc-500">{{ $quiniela->matches_count }} {{ __('matches') }}</span>
                                <span class="text-zinc-500">{{ $quiniela->tickets_count }} {{ __('participants') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layouts::app>
