<x-layouts::app :title="__('Ticket #:id', ['id' => $ticket->id])">
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('tickets.index') }}" wire:navigate class="text-zinc-400 hover:text-white transition-colors">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </a>
                    <flux:heading size="xl" data-test="ticket-heading">{{ __('Ticket #:id', ['id' => $ticket->id]) }}</flux:heading>
                </div>
                <flux:text class="mt-1">
                    <a href="{{ route('quinielas.show', $ticket->quiniela) }}" wire:navigate class="text-accent hover:underline">{{ $ticket->quiniela->name }}</a>
                    · {{ $ticket->quiniela->prediction_type->getLabel() }}
                </flux:text>
            </div>

            @if ($ticket->quiniela->status === \App\Enums\QuinielaStatus::Open)
                <a href="{{ route('tickets.predictions', $ticket) }}" wire:navigate
                   class="inline-flex items-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent/90 transition-colors" data-test="edit-predictions-button">
                    {{ $ticket->predictions->isEmpty() ? __('Submit Predictions') : __('Edit Predictions') }}
                </a>
            @endif
        </div>

        {{-- Score summary --}}
        @if ($ticket->total_points !== null)
            <div class="grid gap-4 grid-cols-2">
                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                    <span class="text-xs text-zinc-400">{{ __('Total Points') }}</span>
                    <div class="text-2xl font-bold mt-1" data-test="total-points">{{ $ticket->total_points }}</div>
                </div>
                @if ($ticket->prize_amount !== null && (float) $ticket->prize_amount > 0)
                    <div class="rounded-xl border border-accent/20 bg-gradient-to-br from-accent/10 to-[var(--color-surface)] p-4">
                        <span class="text-xs text-zinc-400">{{ __('Prize Won') }}</span>
                        <div class="text-2xl font-bold text-accent mt-1" data-test="prize-amount">${{ number_format((float) $ticket->prize_amount, 2) }}</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Predictions list --}}
        <section>
            <flux:heading size="lg" class="mb-4">{{ __('Predictions') }}</flux:heading>

            @if ($ticket->predictions->isEmpty())
                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-8 text-center" data-test="no-predictions">
                    <h3 class="text-lg font-semibold mb-2">{{ __('No predictions yet') }}</h3>
                    <p class="text-zinc-400 text-sm mb-4">{{ __('Submit your predictions for this quiniela.') }}</p>
                    @if ($ticket->quiniela->status === \App\Enums\QuinielaStatus::Open)
                        <a href="{{ route('tickets.predictions', $ticket) }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-white hover:bg-accent/90 transition-colors">
                            {{ __('Submit Predictions') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="space-y-3" data-test="predictions-list">
                    @foreach ($ticket->predictions->sortBy('quinielaMatch.sort_order') as $prediction)
                        @php
                            $match = $prediction->quinielaMatch;
                            $isScoreMode = $ticket->quiniela->prediction_type === \App\Enums\PredictionType::Score;
                        @endphp
                        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4">
                            {{-- Match info --}}
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2 flex-1">
                                    @if ($match->team1->getFirstMediaUrl('logo'))
                                        <img src="{{ $match->team1->getFirstMediaUrl('logo') }}" alt="{{ $match->team1->name }}" class="h-6 w-6 rounded-full object-cover">
                                    @else
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-zinc-800 text-[10px] font-bold">{{ $match->team1->short_name ?? mb_substr($match->team1->name, 0, 3) }}</div>
                                    @endif
                                    <span class="text-sm font-medium truncate">{{ $match->team1->name }}</span>
                                </div>
                                <span class="text-xs text-zinc-500 px-2">vs</span>
                                <div class="flex items-center gap-2 flex-1 justify-end">
                                    <span class="text-sm font-medium truncate text-right">{{ $match->team2->name }}</span>
                                    @if ($match->team2->getFirstMediaUrl('logo'))
                                        <img src="{{ $match->team2->getFirstMediaUrl('logo') }}" alt="{{ $match->team2->name }}" class="h-6 w-6 rounded-full object-cover">
                                    @else
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-zinc-800 text-[10px] font-bold">{{ $match->team2->short_name ?? mb_substr($match->team2->name, 0, 3) }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Prediction & Result --}}
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <span class="text-xs text-zinc-400">{{ __('Your prediction') }}:</span>
                                    @if ($isScoreMode)
                                        <span class="ml-1 font-semibold" data-test="prediction-value">{{ $prediction->predicted_team_1_score }} - {{ $prediction->predicted_team_2_score }}</span>
                                    @else
                                        <span class="ml-1 font-semibold" data-test="prediction-value">{{ $prediction->predicted_result->getLabel() }}</span>
                                    @endif
                                </div>

                                @if ($match->hasResult())
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <span class="text-xs text-zinc-400">{{ __('Result') }}:</span>
                                            <span class="ml-1 font-semibold" data-test="actual-result">{{ $match->team_1_score }} - {{ $match->team_2_score }}</span>
                                        </div>
                                        @if ($prediction->points_earned !== null)
                                            <span @class([
                                                'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $prediction->points_earned > 0,
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $prediction->points_earned < 0,
                                                'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200' => $prediction->points_earned === 0,
                                            ]) data-test="points-earned">
                                                {{ $prediction->points_earned > 0 ? '+' : '' }}{{ $prediction->points_earned }} {{ __('pts') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-layouts::app>
