<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[var(--color-base)] text-white font-sans antialiased">
        {{-- Desktop + Mobile Top Navbar --}}
        <header class="sticky top-0 z-50 border-b border-[var(--color-border-default)] bg-[var(--color-surface)]/95 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-6">
                        {{-- Brand --}}
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 shrink-0">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent">
                                <x-app-logo-icon class="h-5 w-5 text-white" />
                            </div>
                            <span class="text-xl font-bold tracking-tight hidden sm:inline">Tu<span class="text-accent">Quiniela</span></span>
                        </a>

                        {{-- Desktop Nav --}}
                        <nav class="hidden lg:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}" wire:navigate
                               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-white bg-[var(--color-surface-hover)]' : 'text-zinc-400 hover:text-white hover:bg-[var(--color-surface-hover)]' }}">
                                {{ __('Home') }}
                            </a>
                            <a href="#"
                               class="px-4 py-2 text-sm font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-[var(--color-surface-hover)] transition-colors">
                                {{ __('Quinielas') }}
                            </a>
                            <a href="#"
                               class="px-4 py-2 text-sm font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-[var(--color-surface-hover)] transition-colors">
                                {{ __('My Tickets') }}
                            </a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Balance pill --}}
                        <a href="{{ route('balance.history') }}" wire:navigate
                           class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-accent/10 border border-accent/20 hover:bg-accent/20 transition-colors"
                           data-test="navbar-balance">
                            <svg class="h-4 w-4 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-3"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                            <span class="text-sm font-bold text-accent">${{ number_format((float) auth()->user()->balanceFloat, 2) }}</span>
                        </a>

                        {{-- User dropdown --}}
                        <flux:dropdown position="bottom" align="end">
                            <flux:profile
                                :initials="auth()->user()->initials()"
                                icon-trailing="chevron-down"
                            />

                            <flux:menu>
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar
                                        :name="auth()->user()->name"
                                        :initials="auth()->user()->initials()"
                                    />
                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                        <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                    </div>
                                </div>

                                <flux:menu.separator />

                                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                    {{ __('Settings') }}
                                </flux:menu.item>

                                <flux:menu.separator />

                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item
                                        as="button"
                                        type="submit"
                                        icon="arrow-right-start-on-rectangle"
                                        class="w-full cursor-pointer"
                                        data-test="logout-button"
                                    >
                                        {{ __('Log out') }}
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="pb-20 lg:pb-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 py-6 sm:py-8">
                {{ $slot }}
            </div>
        </main>

        {{-- Mobile Bottom Tab Bar --}}
        <x-bottom-tab-bar />

        @fluxScripts
    </body>
</html>
