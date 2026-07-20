<x-layouts.dashboard :title="__('Espace Élève')">
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Mon Espace Élève</h2>
                <p class="font-body-lg text-secondary mt-1">Bonjour, {{ auth()->user()->name }} (Terminale TSE)</p>
            </div>
        </header>

        <!-- Bento Grid Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Academic Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                <div>
                    <h3 class="font-title-md text-secondary mb-2">Moyenne Générale (Trimestre 1)</h3>
                    <div class="flex items-baseline gap-3 mb-1">
                        <span class="font-display-lg text-[48px] font-bold text-primary">14.5</span>
                        <span class="font-body-lg text-secondary">/20</span>
                    </div>
                    <flux:badge color="zinc" class="mt-2 text-primary border border-surface-variant">Mention: Bien</flux:badge>
                </div>
                <div class="mt-8">
                    <flux:button class="w-full sm:w-auto">Voir le bulletin détaillé</flux:button>
                </div>
            </flux:card>

            <!-- Prochains Devoirs Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow bg-primary">
                <div>
                    <h3 class="font-title-md text-on-primary/80 mb-2">Prochain Devoir à Rendre</h3>
                    <p class="font-body-lg text-on-primary mb-4 font-bold text-xl">Exposé d'Histoire</p>
                    <div class="text-on-primary flex items-center gap-2">
                        <flux:icon.clock class="size-5" />
                        A rendre pour Demain, 08h00
                    </div>
                </div>
                <div class="mt-8">
                    <flux:button variant="ghost" class="w-full sm:w-auto text-on-primary hover:bg-on-primary/10">Voir tous les devoirs</flux:button>
                </div>
            </flux:card>
        </div>

        <!-- Latest Grades & School Life -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Dernières Notes Obtenues -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Dernières Notes Obtenues</flux:heading>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                        <span class="font-body-lg text-primary font-medium">Mathématiques</span>
                        <div class="flex items-center gap-3">
                            <span class="font-title-md text-primary font-bold">16/20</span>
                            <flux:badge class="border-2 border-primary text-primary" color="zinc">Excellence</flux:badge>
                        </div>
                    </div>
                    <div class="w-full h-px bg-outline-variant/50"></div>
                    
                    <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                        <span class="font-body-lg text-primary font-medium">Histoire</span>
                        <div class="flex items-center gap-3">
                            <span class="font-title-md text-primary font-bold">12/20</span>
                            <flux:badge class="border-2 border-primary text-primary" color="zinc">Passable</flux:badge>
                        </div>
                    </div>
                    <div class="w-full h-px bg-outline-variant/50"></div>
                    
                    <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                        <span class="font-body-lg text-primary font-medium">Physique</span>
                        <div class="flex items-center gap-3">
                            <span class="font-title-md text-primary font-bold">09/20</span>
                            <flux:badge color="danger" class="border-b-2 border-danger text-danger underline decoration-2 underline-offset-4 bg-transparent">Attention</flux:badge>
                        </div>
                    </div>
                    <div class="w-full h-px bg-outline-variant/50"></div>
                    
                    <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                        <span class="font-body-lg text-primary font-medium">Français</span>
                        <div class="flex items-center gap-3">
                            <span class="font-title-md text-primary font-bold">14/20</span>
                            <flux:badge class="border-2 border-primary text-primary" color="zinc">Bien</flux:badge>
                        </div>
                    </div>
                </div>
            </flux:card>

            <!-- Mon Emploi du Temps -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Mon Emploi du Temps du Jour</flux:heading>
                <div class="flex flex-col gap-4">
                    <!-- Event 1 -->
                    <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-surface-container-low transition-colors group border border-outline-variant">
                        <div class="w-24 flex flex-col text-right pt-1 border-r-2 border-outline-variant pr-4 group-hover:border-primary transition-colors">
                            <span class="font-title-md text-base text-primary">08h00</span>
                            <span class="font-body-sm text-secondary">10h00</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-title-md text-primary">Mathématiques</h4>
                            <p class="font-body-lg text-secondary">M. Traoré - Salle B12</p>
                        </div>
                    </div>
                    
                    <!-- Event 2 -->
                    <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-surface-container-low transition-colors group border border-outline-variant">
                        <div class="w-24 flex flex-col text-right pt-1 border-r-2 border-outline-variant pr-4 group-hover:border-primary transition-colors">
                            <span class="font-title-md text-base text-primary">10h00</span>
                            <span class="font-body-sm text-secondary">12h00</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-title-md text-primary">Physique</h4>
                            <p class="font-body-lg text-secondary">Mme. Diallo - Salle A05</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <flux:link href="#" class="text-sm flex items-center gap-1 text-secondary hover:text-primary">
                        Voir la semaine complète
                        <flux:icon.arrow-right class="size-4" />
                    </flux:link>
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts.dashboard>
