<x-layouts::app :title="$quiniela->name">
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('quinielas.index') }}" wire:navigate class="text-zinc-400 hover:text-white transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </a>
                    <flux:heading size="xl" data-test="quiniela-name">{{ $quiniela->name }}</flux:heading>
                </div>
                <div class="flex items-center gap-3 mt-2">
                    <span @class([
                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $quiniela->status === \App\Enums\QuinielaStatus::Open,
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $quiniela->status === \App\Enums\QuinielaStatus::Closed,
                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $quiniela->status === \App\Enums\QuinielaStatus::Completed,
                    ]) data-test="quiniela-status">
                        {{ $quiniela->status->getLabel() }}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                        {{ $quiniela->prediction_type->getLabel() }}
                    </span>
                </div>
            </div>

            @auth
                @if ($quiniela->status === \App\Enums\QuinielaStatus::Open)
                    <form action="{{ route('tickets.store', $quiniela) }}" method="POST" data-test="buy-ticket-form">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-accent/90 transition-colors">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                            {{ __('Buy Ticket — $:cost', ['cost' => number_format((float) $quiniela->ticket_cost, 2)]) }}
                        </button>
                    </form>
                @endif
            @else
                @if ($quiniela->status === \App\Enums\QuinielaStatus::Open)
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-accent/90 transition-colors">
                        {{ __('Log in to buy a ticket') }}
                    </a>
                @endif
            @endauth
        </div>

        {{-- Flash messages --}}
        @if (session('error'))
            <div class="rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400" data-test="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-lg border border-green-500/30 bg-green-500/10 p-4 text-sm text-green-400" data-test="success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info cards --}}
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                <span class="text-xs text-zinc-400">{{ __('Entry Cost') }}</span>
                <div class="text-xl font-bold text-accent mt-1" data-test="ticket-cost">${{ number_format((float) $quiniela->ticket_cost, 2) }}</div>
            </div>
            <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                <span class="text-xs text-zinc-400">{{ __('Matches') }}</span>
                <div class="text-xl font-bold mt-1" data-test="matches-count">{{ $quiniela->matches->count() }}</div>
            </div>
            <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                <span class="text-xs text-zinc-400">{{ __('Tickets Sold') }}</span>
                <div class="text-xl font-bold mt-1" data-test="tickets-count">{{ $quiniela->tickets_count }}</div>
            </div>
            <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                <span class="text-xs text-zinc-400">{{ __('Closes') }}</span>
                <div class="text-xl font-bold mt-1" data-test="closing-at">{{ $quiniela->closing_at->diffForHumans() }}</div>
            </div>
        </div>

        {{-- Matches list --}}
        <section>
            <flux:heading size="lg" class="mb-4">{{ __('Matches') }}</flux:heading>

            <div class="space-y-3" data-test="matches-list">
                @foreach ($quiniela->matches as $match)
                    <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 flex-1">
                                @if ($match->team1->getFirstMediaUrl('logo'))
                                    <img src="{{ $match->team1->getFirstMediaUrl('logo') }}" alt="{{ $match->team1->name }}" class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-800 text-xs font-bold">{{ $match->team1->short_name ?? mb_substr($match->team1->name, 0, 3) }}</div>
                                @endif
                                <span class="font-medium text-sm" data-test="team1-name">{{ $match->team1->name }}</span>
                            </div>

                            <div class="flex items-center gap-2 px-3 shrink-0">
                                @if ($match->hasResult())
                                    <span class="text-lg font-bold" data-test="match-score">{{ $match->team_1_score }} - {{ $match->team_2_score }}</span>
                                @else
                                    <span class="text-sm text-zinc-500">vs</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 flex-1 justify-end">
                                <span class="font-medium text-sm text-right" data-test="team2-name">{{ $match->team2->name }}</span>
                                @if ($match->team2->getFirstMediaUrl('logo'))
                                    <img src="{{ $match->team2->getFirstMediaUrl('logo') }}" alt="{{ $match->team2->name }}" class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-800 text-xs font-bold">{{ $match->team2->short_name ?? mb_substr($match->team2->name, 0, 3) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 text-center text-xs text-zinc-500">
                            {{ $match->match_date->format('M d, Y — H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Prize Structure --}}
        @if ($quiniela->prizePositions->isNotEmpty())
            <section>
                <flux:heading size="lg" class="mb-4">{{ __('Prize Structure') }}</flux:heading>

                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] overflow-hidden" data-test="prize-structure">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Position') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Prize %') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-zinc-900">
                            @foreach ($quiniela->prizePositions as $prize)
                                <tr>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="font-medium">#{{ $prize->position }}</span> {{ __('Place') }}
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-accent">
                                        {{ number_format((float) $prize->percentage, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
</x-layouts::app>
