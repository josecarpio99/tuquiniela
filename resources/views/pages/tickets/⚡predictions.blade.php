<?php

use App\Enums\MatchResult;
use App\Enums\PredictionType;
use App\Enums\QuinielaStatus;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Submit Predictions')] class extends Component {
    #[Locked]
    public int $ticketId;

    /** @var array<int, string|null> */
    public array $predictions = [];

    /** @var array<int, int|null> */
    public array $scores1 = [];

    /** @var array<int, int|null> */
    public array $scores2 = [];

    public function mount(Ticket $ticket): void
    {
        abort_if($ticket->user_id !== Auth::id(), 403);

        $this->ticketId = $ticket->id;

        $ticket->load([
            'quiniela.matches' => fn ($q) => $q->with('team1', 'team2')->orderBy('sort_order'),
            'predictions',
        ]);

        foreach ($ticket->quiniela->matches as $match) {
            $existing = $ticket->predictions->firstWhere('quiniela_match_id', $match->id);

            $this->predictions[$match->id] = $existing?->predicted_result?->value;
            $this->scores1[$match->id] = $existing?->predicted_team_1_score;
            $this->scores2[$match->id] = $existing?->predicted_team_2_score;
        }
    }

    #[Computed]
    public function ticket(): Ticket
    {
        return Ticket::with([
            'quiniela.matches' => fn ($q) => $q->with('team1', 'team2')->orderBy('sort_order'),
        ])->findOrFail($this->ticketId);
    }

    #[Computed]
    public function isScoreMode(): bool
    {
        return $this->ticket->quiniela->prediction_type === PredictionType::Score;
    }

    #[Computed]
    public function isLocked(): bool
    {
        return $this->ticket->quiniela->status !== QuinielaStatus::Open;
    }

    public function submit(): void
    {
        $ticket = $this->ticket;

        abort_if($ticket->user_id !== Auth::id(), 403);
        abort_if($ticket->quiniela->status !== QuinielaStatus::Open, 403);

        $matches = $ticket->quiniela->matches;

        if ($this->isScoreMode) {
            $rules = [];
            $messages = [];
            foreach ($matches as $match) {
                $rules["scores1.{$match->id}"] = ['required', 'integer', 'min:0'];
                $rules["scores2.{$match->id}"] = ['required', 'integer', 'min:0'];
                $messages["scores1.{$match->id}.required"] = __('Score is required for :team.', ['team' => $match->team1->name ?? __('Team 1')]);
                $messages["scores2.{$match->id}.required"] = __('Score is required for :team.', ['team' => $match->team2->name ?? __('Team 2')]);
                $messages["scores1.{$match->id}.min"] = __('Score must be 0 or greater.');
                $messages["scores2.{$match->id}.min"] = __('Score must be 0 or greater.');
            }
            $this->validate($rules, $messages);
        } else {
            $rules = [];
            $messages = [];
            foreach ($matches as $match) {
                $rules["predictions.{$match->id}"] = ['required', 'in:team1,team2,draw'];
                $messages["predictions.{$match->id}.required"] = __('A prediction is required for every match.');
            }
            $this->validate($rules, $messages);
        }

        foreach ($matches as $match) {
            $data = ['ticket_id' => $ticket->id, 'quiniela_match_id' => $match->id];

            if ($this->isScoreMode) {
                $data['predicted_team_1_score'] = (int) $this->scores1[$match->id];
                $data['predicted_team_2_score'] = (int) $this->scores2[$match->id];
                $data['predicted_result'] = null;
            } else {
                $data['predicted_result'] = $this->predictions[$match->id];
                $data['predicted_team_1_score'] = null;
                $data['predicted_team_2_score'] = null;
            }

            $ticket->predictions()->updateOrCreate(
                ['quiniela_match_id' => $match->id],
                $data,
            );
        }

        session()->flash('success', __('Predictions saved successfully!'));
        $this->redirect(route('tickets.show', $ticket), navigate: true);
    }
}; ?>

@php
    $ticket = $this->ticket;
    $quiniela = $ticket->quiniela;
    $matches = $quiniela->matches;
    $isScoreMode = $this->isScoreMode;
    $isLocked = $this->isLocked;
@endphp

<div class="flex flex-col gap-6">
        {{-- Header --}}
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('tickets.show', $ticket) }}" wire:navigate class="text-zinc-400 hover:text-white transition-colors">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <flux:heading size="xl">{{ __('Submit Predictions') }}</flux:heading>
            </div>
            <flux:text class="mt-1">
                {{ $quiniela->name }} · {{ $quiniela->prediction_type->getLabel() }}
            </flux:text>
        </div>

        @if ($isLocked)
            <div class="rounded-lg border border-yellow-500/30 bg-yellow-500/10 p-4 text-sm text-yellow-400" data-test="locked-message">
                {{ __('This quiniela is closed. Predictions can no longer be edited.') }}
            </div>
        @endif

        <form wire:submit="submit" class="space-y-4">
            @foreach ($matches as $match)
                <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-4" wire:key="match-{{ $match->id }}">
                    {{-- Match header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 flex-1">
                            @if ($match->team1->getFirstMediaUrl('logo'))
                                <img src="{{ $match->team1->getFirstMediaUrl('logo') }}" alt="{{ $match->team1->name }}" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-800 text-xs font-bold">{{ $match->team1->short_name ?? mb_substr($match->team1->name, 0, 3) }}</div>
                            @endif
                            <span class="font-medium text-sm" data-test="team1-name">{{ $match->team1->name }}</span>
                        </div>
                        <span class="text-xs text-zinc-500 px-2">vs</span>
                        <div class="flex items-center gap-2 flex-1 justify-end">
                            <span class="font-medium text-sm text-right" data-test="team2-name">{{ $match->team2->name }}</span>
                            @if ($match->team2->getFirstMediaUrl('logo'))
                                <img src="{{ $match->team2->getFirstMediaUrl('logo') }}" alt="{{ $match->team2->name }}" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-800 text-xs font-bold">{{ $match->team2->short_name ?? mb_substr($match->team2->name, 0, 3) }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Prediction input --}}
                    @if ($isScoreMode)
                        <div class="flex items-center justify-center gap-4">
                            <div class="flex-1 max-w-[100px]">
                                <input type="number" min="0"
                                       wire:model="scores1.{{ $match->id }}"
                                       class="w-full rounded-lg border border-[var(--color-border-default)] bg-zinc-900 px-3 py-2 text-center text-lg font-bold focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                       placeholder="0"
                                       data-test="score1-{{ $match->id }}"
                                       {{ $isLocked ? 'disabled' : '' }}>
                            </div>
                            <span class="text-zinc-500 font-medium">—</span>
                            <div class="flex-1 max-w-[100px]">
                                <input type="number" min="0"
                                       wire:model="scores2.{{ $match->id }}"
                                       class="w-full rounded-lg border border-[var(--color-border-default)] bg-zinc-900 px-3 py-2 text-center text-lg font-bold focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                       placeholder="0"
                                       data-test="score2-{{ $match->id }}"
                                       {{ $isLocked ? 'disabled' : '' }}>
                            </div>
                        </div>
                        @error("scores1.{$match->id}") <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        @error("scores2.{$match->id}") <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    @else
                        <div class="grid grid-cols-3 gap-2" data-test="result-options-{{ $match->id }}">
                            @foreach ([MatchResult::Team1, MatchResult::Draw, MatchResult::Team2] as $result)
                                <label @class([
                                    'flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-medium cursor-pointer transition-all',
                                    'border-accent bg-accent/10 text-accent ring-1 ring-accent' => ($predictions[$match->id] ?? null) === $result->value,
                                    'border-[var(--color-border-default)] text-zinc-400 hover:border-zinc-500 hover:text-white' => ($predictions[$match->id] ?? null) !== $result->value,
                                    'opacity-50 cursor-not-allowed' => $isLocked,
                                ])>
                                    <input type="radio"
                                           wire:model.live="predictions.{{ $match->id }}"
                                           value="{{ $result->value }}"
                                           class="sr-only"
                                           {{ $isLocked ? 'disabled' : '' }}>
                                    @if ($result === MatchResult::Team1)
                                        {{ $match->team1->short_name ?? mb_substr($match->team1->name, 0, 3) }}
                                    @elseif ($result === MatchResult::Draw)
                                        {{ __('Draw') }}
                                    @else
                                        {{ $match->team2->short_name ?? mb_substr($match->team2->name, 0, 3) }}
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        @error("predictions.{$match->id}") <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    @endif

                    <div class="mt-2 text-center text-xs text-zinc-500">
                        {{ $match->match_date->format('M d, Y — H:i') }}
                    </div>
                </div>
            @endforeach

            @if (!$isLocked)
                <div class="sticky bottom-20 lg:bottom-4 z-10">
                    <button type="submit"
                            class="w-full rounded-lg bg-accent px-5 py-3 text-sm font-semibold text-white shadow-lg hover:bg-accent/90 transition-colors"
                            data-test="submit-predictions">
                        <span wire:loading.remove wire:target="submit">{{ __('Save Predictions') }}</span>
                        <span wire:loading wire:target="submit">{{ __('Saving...') }}</span>
                    </button>
                </div>
            @endif
        </form>
    </div>
