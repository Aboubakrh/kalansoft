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

        <!-- Middle Section: Analytics & Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Modes de Paiement breakdown -->
                <flux:card class="p-6">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-surface-variant">
                        <flux:heading size="lg">Répartition des Encaissements par Mode de Paiement</flux:heading>
                        <flux:badge color="zinc">Finances</flux:badge>
                    </div>
                    
                    @if(empty($modesPaiement) || $modesPaiement->isEmpty())
                        <p class="text-secondary text-sm text-center py-6">Aucun paiement enregistré pour le moment.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($modesPaiement as $mode => $total)
                                <div class="p-4 bg-surface-container-low rounded-xl border border-surface-variant">
                                    <span class="text-xs font-bold text-secondary uppercase">{{ $mode ?: 'Non spécifié' }}</span>
                                    <div class="text-xl font-bold text-primary mt-2">
                                        {{ number_format((float)$total, 0, ',', ' ') }} <span class="text-xs font-normal">FCFA</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </flux:card>

                <!-- Top Classes -->
                <flux:card class="p-6">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-surface-variant">
                        <flux:heading size="lg">Effectifs par Classe</flux:heading>
                        <flux:link href="{{ route('admin.classes.index') }}" class="text-xs">Toutes les classes</flux:link>
                    </div>

                    <div class="space-y-3">
                        @forelse($topClasses ?? [] as $cls)
                            <div class="flex items-center justify-between p-3 bg-surface-container-lowest rounded-lg border border-surface-variant">
                                <div>
                                    <span class="font-bold text-primary">{{ $cls->nom }}</span>
                                    <span class="block text-xs text-secondary">{{ $cls->serie->nom ?? 'Général' }}</span>
                                </div>
                                <flux:badge color="blue">{{ $cls->inscriptions_count }} élève(s)</flux:badge>
                            </div>
                        @empty
                            <p class="text-secondary text-sm text-center py-4">Aucune classe configurée.</p>
                        @endforelse
                    </div>
                </flux:card>
            </div>
            
            <!-- Alerts & Gender breakdown -->
            <div>
                <flux:card class="h-full space-y-6">
                    <h4 class="font-label-caps text-secondary uppercase tracking-wider text-xs font-bold">Répartition & Démographie</h4>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-purple-50 border border-purple-100 rounded-xl flex justify-between items-center">
                            <div>
                                <span class="text-xs font-bold text-purple-700 uppercase">Filles</span>
                                <div class="text-2xl font-bold text-purple-900 mt-1">{{ $fillesCount ?? 0 }}</div>
                            </div>
                            <flux:icon.user class="size-8 text-purple-400" />
                        </div>

                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex justify-between items-center">
                            <div>
                                <span class="text-xs font-bold text-blue-700 uppercase">Garçons</span>
                                <div class="text-2xl font-bold text-blue-900 mt-1">{{ $garconsCount ?? 0 }}</div>
                            </div>
                            <flux:icon.user class="size-8 text-blue-400" />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-surface-variant">
                        <h4 class="font-label-caps text-secondary uppercase tracking-wider text-xs font-bold mb-3">Accès Rapide Bulletins</h4>
                        <a href="{{ route('admin.bulletins.index') }}" class="w-full p-3 bg-primary text-on-primary rounded-xl flex items-center justify-center gap-2 font-semibold text-sm hover:opacity-90 transition-opacity">
                            <flux:icon.document-duplicate class="size-4" />
                            Générer les Bulletins PDF
                        </a>
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
