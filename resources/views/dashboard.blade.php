<x-layouts.dashboard :title="__('Dashboard Admin')">
    <!-- Central Dashboard Canvas -->
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        
        <!-- Top Section: KPIs & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <!-- Left: KPIs -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 h-full">
                    <flux:card class="flex flex-col p-6 justify-center">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-body-sm text-secondary">Élèves Inscrits</span>
                            <flux:icon.users class="size-5 text-secondary" />
                        </div>
                        <div class="flex items-end gap-3 mt-4">
                            <span class="font-headline-lg text-primary text-3xl font-bold">{{ number_format($totalEleves ?? 0, 0, ',', ' ') }}</span>
                            <flux:badge color="success" size="sm" icon="arrow-trending-up">+12%</flux:badge>
                        </div>
                    </flux:card>

                    <flux:card class="flex flex-col p-6 justify-center">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-body-sm text-secondary">Professeurs Actifs</span>
                            <flux:icon.book-open class="size-5 text-secondary" />
                        </div>
                        <div class="flex items-end gap-3 mt-4">
                            <span class="font-headline-lg text-primary text-3xl font-bold">{{ $totalProfesseurs ?? 0 }}</span>
                            <flux:badge color="zinc" size="sm">Stable</flux:badge>
                        </div>
                    </flux:card>

                    <flux:card class="flex flex-col p-6 justify-center">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-body-sm text-secondary">Scolarité Encaissée</span>
                            <flux:icon.wallet class="size-5 text-secondary" />
                        </div>
                        <div class="flex items-end gap-3 mt-4">
                            <span class="font-headline-lg text-primary text-3xl font-bold">{{ number_format($scolariteEncaissee ?? 0, 0, ',', ' ') }} <span class="text-lg text-secondary font-normal">FCFA</span></span>
                            <flux:badge color="success" size="sm" icon="arrow-trending-up">+8%</flux:badge>
                        </div>
                    </flux:card>

                    <flux:card class="flex flex-col p-6 justify-center">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-body-sm text-secondary">Taux de Présence</span>
                            <flux:icon.clipboard-document-check class="size-5 text-secondary" />
                        </div>
                        <div class="flex items-end gap-3 mt-4">
                            <span class="font-headline-lg text-primary text-3xl font-bold">{{ $tauxPresence ?? 0 }}%</span>
                            <flux:badge color="danger" size="sm" icon="arrow-trending-down">-2%</flux:badge>
                        </div>
                    </flux:card>
                </div>
            </div>

            <!-- Right Column: Quick Actions & Welcome -->
            <div class="space-y-6">
                <!-- Welcome -->
                <flux:card class="p-6 bg-primary-container text-on-primary-container border-none shadow-none">
                    <h3 class="font-headline-lg text-[20px] mb-2 font-bold">Bonjour, {{ auth()->user()->name ?? 'Admin' }}.</h3>
                    <p class="font-body-sm opacity-90 leading-relaxed">Voici ce qui requiert votre attention aujourd'hui.</p>
                </flux:card>

                <!-- Quick Actions Grid -->
                <div>
                    <h4 class="font-label-caps text-secondary mb-3 uppercase tracking-wider text-xs font-bold">Actions Rapides</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.eleves.create') }}" class="bg-surface-container hover:bg-surface-container-low border border-outline-variant p-4 rounded-xl flex flex-col items-center justify-center gap-2 transition-colors">
                            <flux:icon.user-plus class="size-6 text-primary" />
                            <span class="font-body-sm text-primary font-medium text-center">Nouvel Élève</span>
                        </a>
                        <a href="{{ route('admin.finances.encaisser') }}" class="bg-surface-container hover:bg-surface-container-low border border-outline-variant p-4 rounded-xl flex flex-col items-center justify-center gap-2 transition-colors">
                            <flux:icon.currency-dollar class="size-6 text-primary" />
                            <span class="font-body-sm text-primary font-medium text-center">Encaisser</span>
                        </a>
                        <a href="{{ route('admin.evaluations.index') }}" class="bg-surface-container hover:bg-surface-container-low border border-outline-variant p-4 rounded-xl flex flex-col items-center justify-center gap-2 transition-colors">
                            <flux:icon.document-text class="size-6 text-primary" />
                            <span class="font-body-sm text-primary font-medium text-center">Évaluations</span>
                        </a>
                        <a href="#" class="bg-surface-container hover:bg-surface-container-low border border-outline-variant p-4 rounded-xl flex flex-col items-center justify-center gap-2 transition-colors">
                            <flux:icon.document-duplicate class="size-6 text-primary" />
                            <span class="font-body-sm text-primary font-medium text-center">Bulletins</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2">
                <flux:card class="h-full p-6">
                    <div class="flex justify-between items-center mb-6">
                        <flux:heading size="lg">Évolution des Inscriptions et Recettes</flux:heading>
                        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                    </div>
                    <div class="w-full h-64 bg-surface-container-lowest border border-surface-variant rounded-lg relative overflow-hidden flex items-end">
                        <div class="absolute inset-0 grid grid-cols-6 gap-0 opacity-10">
                            <div class="border-r border-outline-variant"></div><div class="border-r border-outline-variant"></div><div class="border-r border-outline-variant"></div><div class="border-r border-outline-variant"></div><div class="border-r border-outline-variant"></div>
                        </div>
                        <div class="absolute inset-0 flex flex-col justify-between opacity-10">
                            <div class="border-b border-outline-variant"></div><div class="border-b border-outline-variant"></div><div class="border-b border-outline-variant"></div><div class="border-b border-outline-variant"></div>
                        </div>
                        <div class="w-full h-full relative" style="background: linear-gradient(180deg, rgba(0,0,0,0.02) 0%, rgba(255,255,255,0) 100%);">
                            <svg class="absolute w-full h-full bottom-0 left-0" preserveAspectRatio="none" viewBox="0 0 1000 300">
                                <path d="M0,250 C150,250 250,150 400,180 C550,210 650,50 800,90 C900,120 1000,40 1000,40 L1000,300 L0,300 Z" fill="rgba(0,0,0,0.03)" stroke="currentColor" stroke-linejoin="round" stroke-width="2" class="text-primary"></path>
                            </svg>
                        </div>
                    </div>
                </flux:card>
            </div>
            
            <!-- Alerts & Events -->
            <div>
                <flux:card class="h-full">
                    <h4 class="font-label-caps text-secondary mb-4 uppercase tracking-wider text-xs font-bold">Alertes & Agenda</h4>
                    
                    <!-- High Priority Alert -->
                    <div class="bg-error text-on-error p-4 rounded-xl shadow-sm mb-6 flex items-start gap-3">
                        <flux:icon.exclamation-triangle class="size-5 text-on-error shrink-0" />
                        <div>
                            <p class="font-body-sm font-medium">3 professeurs absents ce matin</p>
                            <a class="font-body-sm text-on-error/80 hover:text-on-error underline decoration-1 underline-offset-2 mt-1 inline-block" href="#">Voir les remplacements</a>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="relative pl-3 border-l-2 border-surface-variant space-y-6 ml-2">
                        <!-- Event 1 -->
                        <div class="relative">
                            <div class="absolute -left-[19px] top-1 w-3 h-3 bg-surface-container-lowest border-2 border-primary rounded-full"></div>
                            <div class="pl-4">
                                <p class="font-body-sm text-primary font-medium">Conseil de classe 9ème A</p>
                                <p class="font-body-sm text-secondary text-[12px] mt-0.5">Aujourd'hui, 14h00</p>
                            </div>
                        </div>
                        <!-- Event 2 -->
                        <div class="relative">
                            <div class="absolute -left-[19px] top-1 w-3 h-3 bg-surface-container-lowest border-2 border-outline-variant rounded-full"></div>
                            <div class="pl-4">
                                <p class="font-body-sm text-primary font-medium">Date limite de paiement Tranche 2</p>
                                <p class="font-body-sm text-secondary text-[12px] mt-0.5">Demain, Toute la journée</p>
                            </div>
                        </div>
                    </div>
                </flux:card>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div>
            <flux:card>
                <div class="flex justify-between items-center mb-4">
                    <flux:heading size="lg">Inscriptions Récentes</flux:heading>
                    <flux:link href="{{ route('admin.eleves.index') }}" class="text-sm">Voir tout</flux:link>
                </div>
                
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Élève</flux:table.column>
                        <flux:table.column>Classe</flux:table.column>
                        <flux:table.column>Date</flux:table.column>
                        <flux:table.column>Statut</flux:table.column>
                    </flux:table.columns>
                    
                    <flux:table.rows>
                        @forelse($recentInscriptions ?? [] as $inscription)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    @if($inscription->eleve->user->photo)
                                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . $inscription->eleve->user->photo) }}" alt="photo"/>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                            {{ $inscription->eleve->user->initials() }}
                                        </div>
                                    @endif
                                    <span class="font-body-lg text-primary font-medium">{{ $inscription->eleve->user->nom }} {{ $inscription->eleve->user->prenom }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $inscription->classe->nom ?? 'Non assignée' }}</flux:table.cell>
                            <flux:table.cell>{{ \Carbon\Carbon::parse($inscription->date_inscription)->translatedFormat('d M Y') }}</flux:table.cell>
                            <flux:table.cell><flux:badge size="sm" color="zinc">{{ $inscription->statut }}</flux:badge></flux:table.cell>
                        </flux:table.row>
                        @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center text-secondary py-4">Aucune inscription récente.</flux:table.cell>
                        </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>
    </div>
</x-layouts.dashboard>
