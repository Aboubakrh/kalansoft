<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Mon Espace Élève</h2>
                <p class="font-body-lg text-secondary mt-1">Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->nom }} 
                @if($this->inscriptionActive)
                    ({{ $this->inscriptionActive->classe->nom }})
                @endif
                </p>
            </div>
        </header>

        <!-- Bento Grid Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Academic Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                <div>
                    <h3 class="font-title-md text-secondary mb-2">Moyenne Générale @if($this->derniereMoyenne) (Trimestre {{ $this->derniereMoyenne->trimestre }}) @endif</h3>
                    <div class="flex items-baseline gap-3 mb-1">
                        <span class="font-display-lg text-[48px] font-bold text-primary">
                            {{ $this->derniereMoyenne ? rtrim(rtrim(number_format($this->derniereMoyenne->moyenne, 2, ',', ''), '0'), ',') : '--' }}
                        </span>
                        <span class="font-body-lg text-secondary">/20</span>
                    </div>
                    @if($this->derniereMoyenne && $this->derniereMoyenne->moyenne >= 14)
                        <flux:badge color="zinc" class="mt-2 text-primary border border-surface-variant">Mention: Bien</flux:badge>
                    @endif
                </div>
                <div class="mt-8">
                    <flux:button href="{{ route('eleve.notes') }}" class="w-full sm:w-auto">Voir les bulletins détaillés</flux:button>
                </div>
            </flux:card>

            <!-- Prochains Devoirs Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow bg-primary">
                <div>
                    <h3 class="font-title-md text-on-primary/80 mb-2">Prochain Devoir à Rendre</h3>
                    @if($this->prochainDevoir)
                        <p class="font-body-lg text-on-primary mb-4 font-bold text-xl">{{ $this->prochainDevoir->titre }}</p>
                        <div class="text-on-primary flex items-center gap-2">
                            <flux:icon.clock class="size-5" />
                            A rendre pour le {{ \Carbon\Carbon::parse($this->prochainDevoir->date_remise)->translatedFormat('l j F Y') }}
                        </div>
                    @else
                        <p class="font-body-lg text-on-primary mb-4 font-bold text-xl">Aucun devoir prévu</p>
                        <div class="text-on-primary flex items-center gap-2">
                            <flux:icon.check-circle class="size-5" />
                            Profitez de votre temps libre !
                        </div>
                    @endif
                </div>
                <div class="mt-8">
                    <flux:button variant="ghost" href="{{ route('eleve.cahier-texte') }}" class="w-full sm:w-auto text-on-primary hover:bg-on-primary/10">Voir tous les devoirs</flux:button>
                </div>
            </flux:card>
        </div>

        <!-- Latest Grades & School Life -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Dernières Notes Obtenues -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Dernières Notes Obtenues</flux:heading>
                <div class="space-y-4">
                    @forelse($this->dernieresNotes as $note)
                        <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                            <span class="font-body-lg text-primary font-medium">{{ $note->evaluation->cours->matiere->nom }}</span>
                            <div class="flex items-center gap-3">
                                <span class="font-title-md text-primary font-bold">
                                    {{ rtrim(rtrim(number_format($note->valeur, 2, ',', ''), '0'), ',') }}/{{ rtrim(rtrim(number_format($note->evaluation->bareme, 2, ',', ''), '0'), ',') }}
                                </span>
                                @php
                                    $ratio = $note->valeur / $note->evaluation->bareme;
                                @endphp
                                @if($ratio >= 0.75)
                                    <flux:badge class="border-2 border-primary text-primary" color="zinc">Excellent</flux:badge>
                                @elseif($ratio >= 0.6)
                                    <flux:badge class="border-2 border-primary text-primary" color="zinc">Bien</flux:badge>
                                @elseif($ratio < 0.5)
                                    <flux:badge color="danger" class="border-b-2 border-danger text-danger underline decoration-2 underline-offset-4 bg-transparent">Attention</flux:badge>
                                @else
                                    <flux:badge class="border-2 border-primary text-primary" color="zinc">Passable</flux:badge>
                                @endif
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="w-full h-px bg-outline-variant/50"></div>
                        @endif
                    @empty
                        <div class="text-center p-4 text-secondary italic">Aucune note enregistrée récemment.</div>
                    @endforelse
                </div>
                <div class="mt-6 flex justify-end">
                    <flux:link href="{{ route('eleve.notes') }}" class="text-sm flex items-center gap-1 text-secondary hover:text-primary">
                        Voir toutes mes notes
                        <flux:icon.arrow-right class="size-4" />
                    </flux:link>
                </div>
            </flux:card>

            <!-- Mon Emploi du Temps -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Mon Emploi du Temps du Jour</flux:heading>
                <div class="flex flex-col gap-4">
                    @forelse($this->emploiDuTempsJour as $seance)
                        <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-surface-container-low transition-colors group border border-outline-variant">
                            <div class="w-24 flex flex-col text-right pt-1 border-r-2 border-outline-variant pr-4 group-hover:border-primary transition-colors">
                                <span class="font-title-md text-base text-primary">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}</span>
                                <span class="font-body-sm text-secondary">{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-title-md text-primary">{{ $seance->cours->matiere->nom }}</h4>
                                <p class="font-body-lg text-secondary">
                                    {{ $seance->cours->enseignant->user->prenom ?? '' }} {{ $seance->cours->enseignant->user->nom ?? '' }} - 
                                    {{ $seance->salle->nom ?? 'Non définie' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-4 text-secondary italic">Aucun cours prévu aujourd'hui.</div>
                    @endforelse
                </div>
                
                <div class="mt-6 flex justify-end">
                    <flux:link href="{{ route('eleve.emploi-du-temps') }}" class="text-sm flex items-center gap-1 text-secondary hover:text-primary">
                        Voir la semaine complète
                        <flux:icon.arrow-right class="size-4" />
                    </flux:link>
                </div>
            </flux:card>
        </div>
    </div>
</div>
