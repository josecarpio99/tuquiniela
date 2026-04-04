<x-layouts::app :title="__('Dashboard')">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ __('Hello, :name!', ['name' => auth()->user()->name]) }} 👋</h1>
        <p class="text-zinc-400">{{ __('Here is a summary of your activity') }}</p>
    </div>

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
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Active tickets') }}</span>
            </div>
            <div class="text-3xl font-bold">0</div>
        </div>

        {{-- Prizes Won --}}
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Prizes won') }}</span>
            </div>
            <div class="text-3xl font-bold text-yellow-400">$0.00</div>
        </div>

        {{-- Quinielas Played --}}
        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-5 w-5 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg>
                <span class="text-sm text-zinc-400">{{ __('Quinielas played') }}</span>
            </div>
            <div class="text-3xl font-bold">0</div>
        </div>
    </div>

    {{-- Empty state --}}
    <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-8 text-center">
        <div class="flex items-center justify-center h-12 w-12 mx-auto mb-4 rounded-xl bg-accent/10">
            <svg class="h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
        </div>
        <h3 class="text-lg font-semibold mb-2">{{ __('No active quinielas yet') }}</h3>
        <p class="text-zinc-400 text-sm mb-4">{{ __('When quinielas are available, they will appear here.') }}</p>
    </div>
</x-layouts::app>
