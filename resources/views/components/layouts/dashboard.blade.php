<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'KalanSoft - Tableau de Bord' }}</title>

    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-on-surface antialiased font-sans flex overflow-hidden">
    
    <!-- Sidebar -->
    <flux:sidebar stashable class="bg-surface-container-lowest border-r border-surface-variant overflow-y-auto flex flex-col h-screen">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="flex items-center px-2 pb-4 mb-2" data-flux-brand>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 shrink-0 bg-primary rounded-lg flex items-center justify-center text-on-primary">
                    <flux:icon.academic-cap class="size-6" />
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="font-display-lg text-[20px] leading-none text-primary tracking-tight font-bold">KalanSoft</h1>
                    <p class="font-body-sm text-[11px] leading-tight text-secondary mt-0.5">Gestion scolaire</p>
                </div>
            </div>
        </a>

        <flux:navlist variant="outline" :accent="false">
            <!-- Navigation Role-Based -->
            @auth
                @if(auth()->user()->hasRole('admin'))
                    @include('components.layouts.partials.sidebar-admin')
                @elseif(auth()->user()->hasRole('professeur'))
                    @include('components.layouts.partials.sidebar-professeur')
                @elseif(auth()->user()->hasRole('parent'))
                    @include('components.layouts.partials.sidebar-parent')
                @elseif(auth()->user()->hasRole('eleve'))
                    @include('components.layouts.partials.sidebar-eleve')
                @endif
            @endauth
        </flux:navlist>

        <flux:spacer />

        <flux:dropdown position="top" align="start" class="mb-4">
            <flux:profile avatar="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" name="{{ auth()->user()->name ?? 'Administrateur' }}" />
            <flux:menu>
                <flux:menu.item icon="user">Mon Profil</flux:menu.item>
                <flux:menu.item icon="cog-6-tooth">Paramètres</flux:menu.item>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item icon="arrow-right-start-on-rectangle" onclick="event.preventDefault(); this.closest('form').submit();">Déconnexion</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top App Bar -->
        <flux:header class="h-16 border-b border-surface-variant bg-surface/80 backdrop-blur-md flex justify-between items-center px-6 sticky top-0 z-40">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-3" />
            <h2 class="font-headline-lg text-[24px] text-primary hidden md:block">Vue d'ensemble</h2>
            
            <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                <div class="hidden sm:block w-full max-w-md md:w-64">
                    <flux:input icon="magnifying-glass" placeholder="Rechercher..." />
                </div>
                <div class="flex items-center gap-2">
                    <flux:button variant="ghost" icon="bell" class="text-secondary hover:text-primary" />
                    <flux:button variant="ghost" icon="question-mark-circle" class="text-secondary hover:text-primary" />
                </div>
            </div>
        </flux:header>

        <!-- Main Content -->
        <main class="p-0 overflow-y-auto flex-1 w-full bg-surface flex flex-col">
            {{ $slot }}
        </main>
    </div>

    @fluxScripts
</body>
</html>
