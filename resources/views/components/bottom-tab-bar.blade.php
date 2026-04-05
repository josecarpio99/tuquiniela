<nav class="fixed bottom-0 inset-x-0 z-50 lg:hidden bg-[var(--color-surface)]/95 backdrop-blur-md border-t border-[var(--color-border-default)]"
     style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom, 0px));">
    <div class="flex items-center justify-around h-16 px-2">
        @auth
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 {{ request()->routeIs('dashboard') ? 'text-accent' : 'text-zinc-500 hover:text-zinc-300' }} transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span class="text-[10px] font-medium">{{ __('Home') }}</span>
            </a>
        @endauth
        <a href="{{ route('quinielas.index') }}" wire:navigate
           class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 {{ request()->routeIs('quinielas.*') ? 'text-accent' : 'text-zinc-500 hover:text-zinc-300' }} transition-colors">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C7 4 6 9 6 9Z"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5C17 4 18 9 18 9Z"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
            <span class="text-[10px] font-medium">{{ __('Quinielas') }}</span>
        </a>
        @auth
            <a href="{{ route('tickets.index') }}" wire:navigate
               class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 {{ request()->routeIs('tickets.*') ? 'text-accent' : 'text-zinc-500 hover:text-zinc-300' }} transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                <span class="text-[10px] font-medium">{{ __('Tickets') }}</span>
            </a>
            <a href="{{ route('balance.history') }}" wire:navigate
               class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 {{ request()->routeIs('balance.history') ? 'text-accent' : 'text-zinc-500 hover:text-zinc-300' }} transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-3"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                <span class="text-[10px] font-medium">{{ __('Balance') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" wire:navigate
               class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 {{ request()->routeIs('profile.edit', 'security.edit') ? 'text-accent' : 'text-zinc-500 hover:text-zinc-300' }} transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                <span class="text-[10px] font-medium">{{ __('Profile') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate
               class="flex flex-col items-center justify-center gap-0.5 min-w-[4rem] py-1 text-zinc-500 hover:text-zinc-300 transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span class="text-[10px] font-medium">{{ __('Log in') }}</span>
            </a>
        @endauth
    </div>
</nav>
