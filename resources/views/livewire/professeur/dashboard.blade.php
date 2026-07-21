<div class="flex w-full h-full">
    <!-- Central Dashboard Canvas -->
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-end mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Bonjour, {{ auth()->user()->name }}.</h2>
                <p class="font-body-lg text-secondary mt-1">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
        </header>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <flux:card class="flex flex-col p-6">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.clock class="size-5 text-outline" />
                    <span class="font-body-sm text-secondary">Heures de cours (Semaine)</span>
                </div>
                <div class="font-display-lg text-[32px] text-primary mt-2">{{ $heuresSemaine }}h</div>
            </flux:card>

            <flux:card class="flex flex-col p-6">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <flux:icon.clipboard-document-list class="size-5 text-outline" />
                        <span class="font-body-sm text-secondary">Évaluations en attente</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <span class="font-display-lg text-[32px] {{ $evaluationsAttente > 0 ? 'text-primary' : 'text-secondary' }}">{{ $evaluationsAttente }}</span>
                    @if($evaluationsAttente > 0)
                        <flux:badge color="zinc" class="border-2 border-primary text-primary">À CORRIGER</flux:badge>
                    @endif
                </div>
            </flux:card>

            <flux:card class="flex flex-col p-6">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.user-check class="size-5 text-outline" />
                    <span class="font-body-sm text-secondary">Taux de présence moyen</span>
                </div>
                <div class="font-display-lg text-[32px] text-primary mt-2">{{ $tauxPresence }}%</div>
            </flux:card>
        </div>

        <!-- Mon Emploi du Temps du Jour -->
        <flux:card class="mb-8 p-6">
            <div class="flex items-center gap-2 mb-6">
                <flux:icon.calendar class="size-6 text-primary" />
                <flux:heading size="lg">Mon Emploi du Temps du Jour</flux:heading>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($emploiDuTempsJour as $index => $seance)
                    <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <div class="w-24 flex flex-col text-right pt-1 border-r-2 border-outline-variant pr-4 group-hover:border-primary transition-colors">
                            <span class="font-title-md text-base text-primary">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H\hi') }}</span>
                            <span class="font-body-sm text-secondary">{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H\hi') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-title-md text-primary">{{ $seance->classe->nom ?? 'Classe introuvable' }}</h4>
                            <p class="font-body-lg text-secondary">{{ $seance->cours->matiere->nom ?? 'Matière introuvable' }} - Salle {{ $seance->salle->nom ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if(!$loop->last)
                        <div class="w-full h-px bg-outline-variant ml-28"></div>
                    @endif
                @empty
                    <div class="text-center py-6 text-secondary">
                        <p>Aucun cours prévu pour aujourd'hui.</p>
                    </div>
                @endforelse
            </div>
        </flux:card>

        <!-- Cahier de texte - Dernières saisies -->
        <flux:card class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <flux:icon.book-open class="size-6 text-primary" />
                    <flux:heading size="lg">Cahier de texte - Dernières saisies</flux:heading>
                </div>
                <flux:button variant="primary" href="{{ route('professeur.cahier-texte') }}">Remplir pour aujourd'hui</flux:button>
            </div>

            <div class="flex flex-col gap-0 border border-outline-variant rounded-lg overflow-hidden">
                @forelse($cahiersTexte as $cahier)
                    <div class="p-4 bg-surface-container-lowest border-b border-outline-variant hover:bg-surface-container-low transition-colors flex justify-between items-center">
                        <div>
                            <p class="font-title-md text-base text-primary">{{ $cahier->classe->nom ?? 'N/A' }} ({{ $cahier->cours->matiere->nom ?? 'N/A' }})</p>
                            <p class="font-body-sm text-secondary">{{ Str::limit($cahier->contenu, 60) }}</p>
                        </div>
                        <span class="font-body-sm text-secondary">{{ \Carbon\Carbon::parse($cahier->date_saisie)->translatedFormat('D. d M') }}</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-secondary">
                        <p>Aucune saisie récente dans le cahier de texte.</p>
                    </div>
                @endforelse
            </div>
        </flux:card>
    </div>

    <!-- Right Contextual Panel -->
    <aside class="w-80 border-l border-surface-variant bg-surface-container-lowest overflow-y-auto hidden xl:block shrink-0 h-full">
        <div class="p-6">
            <!-- Priority Action -->
            <div class="mb-8">
                <a href="{{ route('professeur.presences') }}" class="w-full bg-primary text-on-primary py-4 rounded-xl font-title-md hover:opacity-90 transition-opacity shadow-[0_10px_15px_-3px_rgba(0,0,0,0.1)] flex items-center justify-center gap-2">
                    <flux:icon.clipboard-document-check class="size-6" />
                    Faire l'appel
                </a>
            </div>

            <!-- Classes principales -->
            <div class="mb-8">
                <h3 class="font-label-caps text-secondary uppercase mb-4 tracking-wider text-xs font-bold">Classes principales</h3>
                <div class="flex flex-col gap-3">
                    @forelse($classes as $classe)
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 hover:shadow-[0_10px_15px_-3px_rgba(0,0,0,0.05)] transition-shadow cursor-pointer">
                            <div class="flex justify-between items-center">
                                <p class="font-title-md text-base text-primary">{{ $classe->nom }}</p>
                                <flux:icon.chevron-right class="size-5 text-outline" />
                            </div>
                            <p class="font-body-sm text-secondary mt-1">Série: {{ $classe->serie->nom ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <div class="text-sm text-secondary">Vous n'avez pas de classes assignées pour le moment.</div>
                    @endforelse
                </div>
            </div>

            <!-- Notifications -->
            <div>
                <h3 class="font-label-caps text-secondary uppercase mb-4 tracking-wider text-xs font-bold">Notifications</h3>
                @forelse($notifications as $notification)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-4 flex gap-3 items-start mb-3">
                        <flux:icon.megaphone class="size-5 text-primary mt-0.5 shrink-0" />
                        <div>
                            <p class="font-title-md text-sm text-primary">{{ $notification->title ?? 'Notification' }}</p>
                            <p class="font-body-sm text-secondary mt-1">{{ $notification->message ?? 'Détails non disponibles' }}</p>
                            @if(!empty($notification->action_url))
                                <a class="text-primary font-bold text-sm mt-2 inline-block hover:underline" href="{{ $notification->action_url }}">Voir plus</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-secondary">Aucune notification pour le moment.</div>
                @endforelse
            </div>
        </div>
    </aside>
</div>
