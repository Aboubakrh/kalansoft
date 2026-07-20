<x-layouts.dashboard :title="__('Espace Famille')">
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <!-- Header & Child Selector -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Espace Famille</h2>
                <p class="font-body-lg text-secondary mt-1">Bonjour, {{ auth()->user()->name }}</p>
            </div>
            
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" class="w-full md:w-auto text-left">
                    Sélectionner un enfant : <strong class="ml-1">Seydou</strong>
                </flux:button>
                <flux:menu>
                    <flux:menu.item>Seydou (Terminale TSE)</flux:menu.item>
                    <flux:menu.item>Awa (10ème Commune A)</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
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

            <!-- Financial Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                <div>
                    <h3 class="font-title-md text-secondary mb-2">Situation Financière</h3>
                    <p class="font-body-lg text-primary mb-4">2ème Tranche à régler avant le 15 Janvier</p>
                    <div class="font-display-lg text-[48px] font-bold text-primary flex items-baseline gap-2">
                        50,000 <span class="font-title-md text-[20px] text-secondary font-normal">FCFA</span>
                    </div>
                </div>
                <div class="mt-8">
                    <flux:button variant="primary" class="w-full sm:w-auto">Payer maintenant</flux:button>
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

            <!-- Vie Scolaire (Attendance) -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Vie Scolaire</flux:heading>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Date & Heure</flux:table.column>
                        <flux:table.column>Type d'incident</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        <flux:table.row>
                            <flux:table.cell class="text-secondary">Lundi 12, 08h00</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                    <span class="font-medium text-primary">Retard (Non justifié)</span>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                        <flux:table.row>
                            <flux:table.cell class="text-secondary">Jeudi 15, 14h00</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-outline"></div>
                                    <span class="font-medium text-primary">Absence (Justifiée)</span>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                </flux:table>
                
                <div class="mt-6 flex justify-end">
                    <flux:link href="#" class="text-sm flex items-center gap-1 text-secondary hover:text-primary">
                        Voir tout l'historique
                        <flux:icon.arrow-right class="size-4" />
                    </flux:link>
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts.dashboard>
