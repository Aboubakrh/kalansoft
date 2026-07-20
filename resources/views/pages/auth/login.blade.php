<x-layouts.auth-split :title="__('Log in')">
<div class="h-screen w-full flex">
    <!-- Left Panel: Login Form -->
    <div class="w-full lg:w-1/2 bg-surface-container-lowest flex items-center justify-center p-8">
        <div class="max-w-md w-full">
            <!-- Brand Logo -->
            <div class="flex items-center gap-5 mb-14">
                <div class="w-24 h-24 shrink-0 bg-primary rounded-[20px] flex items-center justify-center text-on-primary shadow-lg">
                    <flux:icon.academic-cap class="size-16" />
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="font-display-lg text-[48px] leading-none text-primary tracking-tighter font-black">KalanSoft</h1>
                    <p class="font-body-sm text-[16px] leading-tight text-secondary mt-1 font-medium tracking-wide uppercase">Gestion scolaire</p>
                </div>
            </div>
            
            <!-- Welcome Text -->
            <div>
                <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Bon retour parmi nous.</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Veuillez entrer vos identifiants pour accéder à votre espace.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mt-4" :status="session('status')" />

            <!-- Form -->
            <form action="{{ route('login.store') }}" class="mt-8 space-y-6" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <flux:input
                        name="email"
                        label="Adresse Email"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="aboubacar@kalansoft.ml"
                    />
                </div>
                
                <!-- Password Field -->
                <div class="relative">
                    <flux:input
                        name="password"
                        label="Mot de passe"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        viewable
                    />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                            Mot de passe oublié ?
                        </flux:link>
                    @endif
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between mt-6">
                    <flux:checkbox name="remember" label="Se souvenir de moi" :checked="old('remember')" />
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <flux:button variant="primary" type="submit" class="w-full">
                        Se connecter
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right Panel: Visual/Dashboard Preview -->
    <div class="hidden lg:flex lg:w-1/2 bg-surface items-center justify-center p-12 relative overflow-hidden">
        <!-- Subtle Background Pattern/Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-surface-container-low to-surface-container-high opacity-50"></div>
        
        <!-- Bento Card -->
        <div class="relative w-full max-w-lg bg-surface-container-lowest rounded-[24px] shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-outline-variant p-8 overflow-hidden group hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.12)] transition-shadow duration-500">
            <!-- Card Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h3 class="font-title-md text-title-md text-primary">Aperçu Global</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Année Académique 2023-2024</p>
                </div>
                <!-- Badge -->
                <div class="inline-flex items-center px-3 py-1 rounded-full border border-primary bg-surface-container-lowest text-primary font-label-caps text-label-caps">
                    <span class="material-symbols-outlined text-[14px] mr-1" data-icon="trending_up">trending_up</span>
                    +15% Inscriptions
                </div>
            </div>
            
            <!-- Main Metric -->
            <div class="mb-10">
                <div class="font-display-lg text-display-lg text-primary tracking-tight">1,245</div>
                <div class="font-body-lg text-body-lg text-on-surface-variant font-medium">Élèves actifs</div>
            </div>
            
            <!-- Visual Progress Bars -->
            <div class="space-y-5">
                <!-- Stat 1 -->
                <div>
                    <div class="flex justify-between font-body-sm text-body-sm mb-2">
                        <span class="text-on-surface font-medium">Taux de Présence</span>
                        <span class="text-primary font-bold">94%</span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                        <div class="bg-primary h-2 rounded-full" style="width: 94%"></div>
                    </div>
                </div>
                <!-- Stat 2 -->
                <div>
                    <div class="flex justify-between font-body-sm text-body-sm mb-2">
                        <span class="text-on-surface font-medium">Recouvrement Frais</span>
                        <span class="text-primary font-bold">82%</span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                        <div class="bg-primary h-2 rounded-full" style="width: 82%"></div>
                    </div>
                </div>
                <!-- Stat 3 -->
                <div>
                    <div class="flex justify-between font-body-sm text-body-sm mb-2">
                        <span class="text-on-surface font-medium">Capacité Salles</span>
                        <span class="text-primary font-bold">68%</span>
                    </div>
                    <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                        <div class="bg-primary h-2 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Decorative overlay -->
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-surface-container-high rounded-full opacity-20 blur-2xl pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-primary rounded-full opacity-[0.03] blur-xl pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
        </div>
    </div>
</div>
</x-layouts.auth-split>
