@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="TuQuiniela" {{ $attributes }}>
        <x-slot name="logo" class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent-content">
            <x-app-logo-icon class="h-5 w-5 text-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="null" {{ $attributes }}>
        <x-slot name="logo" class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent-content">
            <x-app-logo-icon class="h-5 w-5 text-white" />
        </x-slot>
        <span class="text-xl font-bold tracking-tight">Tu<span class="text-accent">Quiniela</span></span>
    </flux:brand>
@endif
