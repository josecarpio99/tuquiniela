<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            html { scroll-behavior: smooth; }
            .glow-accent { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), 0 0 60px rgba(16, 185, 129, 0.1); }
            .text-gradient { background: linear-gradient(135deg, #10B981 0%, #34D399 50%, #6EE7B7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
            .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
            .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); }
            @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
            .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
        </style>
    </head>
    <body class="bg-[var(--color-base)] text-white font-sans antialiased">

        {{-- TOP NAVBAR --}}
        <header class="sticky top-0 z-50 border-b border-[var(--color-border-default)] bg-[var(--color-surface)]/95 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="flex h-16 items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent">
                            <x-app-logo-icon class="h-5 w-5 text-white" />
                        </div>
                        <span class="text-xl font-bold tracking-tight">Tu<span class="text-accent">Quiniela</span></span>
                    </a>

                    <nav class="hidden lg:flex items-center gap-1 ml-10">
                        <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-white rounded-lg bg-[var(--color-surface-hover)]">{{ __('Home') }}</a>
                        <a href="#quinielas" class="px-4 py-2 text-sm font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-[var(--color-surface-hover)] transition-colors">{{ __('Quinielas') }}</a>
                        <a href="#como-funciona" class="px-4 py-2 text-sm font-medium text-zinc-400 hover:text-white rounded-lg hover:bg-[var(--color-surface-hover)] transition-colors">{{ __('How it works') }}</a>
                    </nav>

                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-lg transition-colors">
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <div class="hidden sm:flex items-center gap-2">
                                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-300 hover:text-white rounded-lg border border-[var(--color-border-default)] hover:border-zinc-500 transition-colors">
                                    {{ __('Log in') }}
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-lg transition-colors">
                                        {{ __('Sign up') }}
                                    </a>
                                @endif
                            </div>
                            <a href="{{ route('login') }}" class="sm:hidden flex items-center justify-center h-10 w-10 rounded-lg border border-[var(--color-border-default)] hover:border-zinc-500 text-zinc-400 hover:text-white transition-colors">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
            {{-- HERO SECTION --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-accent/5 via-transparent to-transparent"></div>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-accent/5 rounded-full blur-3xl"></div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pt-16 pb-20 sm:pt-24 sm:pb-28">
                    <div class="mx-auto max-w-3xl text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-full bg-accent/10 border border-accent/30 text-accent text-sm font-medium">
                            <span class="flex h-2 w-2 rounded-full bg-accent pulse-dot"></span>
                            {{ __('Open quinielas now') }}
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight leading-tight mb-6">
                            {{ __('Predict the results,') }}<br>
                            <span class="text-gradient">{{ __('win real prizes') }}</span>
                        </h1>

                        <p class="text-lg sm:text-xl text-zinc-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                            {{ __('The football quiniela platform where your sports knowledge becomes earnings. Predict results, accumulate points and take the prize.') }}
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" wire:navigate class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-xl transition-colors glow-accent text-center">
                                    {{ __('Go to dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-xl transition-colors glow-accent text-center">
                                    {{ __('Sign up free') }}
                                </a>
                            @endauth
                            <a href="#como-funciona" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold text-zinc-300 hover:text-white border border-[var(--color-border-default)] hover:border-zinc-500 rounded-xl transition-colors text-center">
                                {{ __('How does it work?') }}
                            </a>
                        </div>

                        <div class="flex items-center justify-center gap-8 sm:gap-12 mt-12 pt-8 border-t border-[var(--color-border-default)]/50">
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-white">1,200+</div>
                                <div class="text-sm text-zinc-500 mt-1">{{ __('Active players') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-accent">$15,000</div>
                                <div class="text-sm text-zinc-500 mt-1">{{ __('Prizes awarded') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold text-white">50+</div>
                                <div class="text-sm text-zinc-500 mt-1">{{ __('Completed quinielas') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- HOW IT WORKS --}}
            <section id="como-funciona" class="py-16 sm:py-20 border-t border-[var(--color-border-default)]/50">
                <div class="mx-auto max-w-7xl px-4 sm:px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-2xl sm:text-3xl font-bold">{{ __('How does it work?') }}</h2>
                        <p class="text-zinc-400 mt-2">{{ __('In 3 simple steps you will be competing') }}</p>
                    </div>

                    <div class="grid gap-8 sm:gap-6 sm:grid-cols-3 max-w-4xl mx-auto">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 mx-auto mb-5 rounded-2xl bg-accent/10 border border-accent/20">
                                <svg class="h-8 w-8 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                            </div>
                            <div class="inline-flex items-center justify-center h-6 w-6 mb-3 rounded-full bg-accent text-white text-xs font-bold">1</div>
                            <h3 class="text-lg font-semibold mb-2">{{ __('Register') }}</h3>
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ __('Create your free account in seconds. You only need an email.') }}</p>
                        </div>

                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 mx-auto mb-5 rounded-2xl bg-accent/10 border border-accent/20">
                                <svg class="h-8 w-8 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </div>
                            <div class="inline-flex items-center justify-center h-6 w-6 mb-3 rounded-full bg-accent text-white text-xs font-bold">2</div>
                            <h3 class="text-lg font-semibold mb-2">{{ __('Deposit') }}</h3>
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ __('Add funds to your balance with Binance Pay or other payment methods.') }}</p>
                        </div>

                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 mx-auto mb-5 rounded-2xl bg-accent/10 border border-accent/20">
                                <svg class="h-8 w-8 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            </div>
                            <div class="inline-flex items-center justify-center h-6 w-6 mb-3 rounded-full bg-accent text-white text-xs font-bold">3</div>
                            <h3 class="text-lg font-semibold mb-2">{{ __('Play and Win!') }}</h3>
                            <p class="text-zinc-400 text-sm leading-relaxed">{{ __('Buy your ticket, make your predictions and win prizes if you guess right.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- QUINIELA TYPES --}}
            <section class="py-16 sm:py-20 border-t border-[var(--color-border-default)]/50">
                <div class="mx-auto max-w-7xl px-4 sm:px-6">
                    <div class="text-center mb-12">
                        <h2 class="text-2xl sm:text-3xl font-bold">{{ __('Quiniela Types') }}</h2>
                        <p class="text-zinc-400 mt-2">{{ __('Two ways to show your knowledge') }}</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 max-w-4xl mx-auto">
                        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500/10 text-blue-400">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <h3 class="text-lg font-semibold">{{ __('By Result') }}</h3>
                            </div>
                            <p class="text-zinc-400 text-sm mb-4 leading-relaxed">{{ __('Predict who wins each match: Home, Away or Draw. Simple and direct.') }}</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                    <span class="text-zinc-300">✅ {{ __('Correct') }}</span>
                                    <span class="text-accent font-semibold">+1 {{ __('point') }}</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                    <span class="text-zinc-300">❌ {{ __('Wrong') }}</span>
                                    <span class="text-zinc-500 font-semibold">0 {{ __('points') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-[var(--color-border-default)] bg-[var(--color-surface)] p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-purple-500/10 text-purple-400">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg>
                                </div>
                                <h3 class="text-lg font-semibold">{{ __('By Score') }}</h3>
                            </div>
                            <p class="text-zinc-400 text-sm mb-4 leading-relaxed">{{ __('Predict the exact score of each match. Higher risk, higher reward.') }}</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                    <span class="text-zinc-300">🎯 {{ __('Exact score') }}</span>
                                    <span class="text-accent font-semibold">+4 {{ __('points') }}</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                    <span class="text-zinc-300">✅ {{ __('Correct result') }}</span>
                                    <span class="text-blue-400 font-semibold">+2 {{ __('points') }}</span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[var(--color-base)] text-sm">
                                    <span class="text-zinc-300">❌ {{ __('Wrong') }}</span>
                                    <span class="text-red-400 font-semibold">-1 {{ __('point') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA SECTION --}}
            <section class="py-16 sm:py-20 border-t border-[var(--color-border-default)]/50">
                <div class="mx-auto max-w-7xl px-4 sm:px-6">
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-accent/20 via-[var(--color-surface)] to-[var(--color-surface)] border border-accent/20 p-8 sm:p-12 text-center">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-accent/5 rounded-full blur-3xl"></div>

                        <div class="relative">
                            <h2 class="text-2xl sm:text-3xl font-bold mb-4">{{ __('Ready to show what you know?') }}</h2>
                            <p class="text-zinc-400 max-w-xl mx-auto mb-8 leading-relaxed">
                                {{ __('Join thousands of players already winning with their predictions. Sign up free and start playing today.') }}
                            </p>
                            @auth
                                <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex px-8 py-3.5 text-base font-semibold text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-xl transition-colors glow-accent">
                                    {{ __('Go to dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex px-8 py-3.5 text-base font-semibold text-white bg-accent hover:bg-[var(--color-accent-hover,#059669)] rounded-xl transition-colors glow-accent">
                                    {{ __('Create my free account') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-[var(--color-border-default)] bg-[var(--color-surface)] py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent">
                                <x-app-logo-icon class="h-4 w-4 text-white" />
                            </div>
                            <span class="text-lg font-bold">Tu<span class="text-accent">Quiniela</span></span>
                        </a>
                        <p class="text-sm text-zinc-500 leading-relaxed">
                            {{ __('The most exciting football prediction platform. Predict, compete and win.') }}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-300 mb-4">{{ __('Platform') }}</h4>
                        <ul class="space-y-2.5">
                            <li><a href="#quinielas" class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors">{{ __('Quinielas') }}</a></li>
                            <li><a href="#como-funciona" class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors">{{ __('How it works') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-300 mb-4">{{ __('Account') }}</h4>
                        <ul class="space-y-2.5">
                            <li><a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors">{{ __('Log in') }}</a></li>
                            @if (Route::has('register'))
                                <li><a href="{{ route('register') }}" class="text-sm text-zinc-500 hover:text-zinc-300 transition-colors">{{ __('Sign up') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-300 mb-4">{{ __('Legal') }}</h4>
                        <ul class="space-y-2.5">
                            <li><span class="text-sm text-zinc-500">{{ __('Terms and conditions') }}</span></li>
                            <li><span class="text-sm text-zinc-500">{{ __('Privacy policy') }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10 pt-6 border-t border-[var(--color-border-default)] text-center sm:text-left">
                    <p class="text-sm text-zinc-600">&copy; {{ date('Y') }} TuQuiniela. {{ __('All rights reserved.') }}</p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
