<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="mb-8">
                <h2 class="font-headline-lg text-[24px] text-primary">Mon Emploi du Temps</h2>
                <p class="font-body-lg text-secondary mt-1">Consultez votre planning hebdomadaire.</p>
            </header>

            @if($this->inscriptionActive)
                @php
                    $seancesParJour = $this->seances->groupBy('jour');
                @endphp

                @if($seancesParJour->isEmpty())
                    <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                        <flux:icon.calendar class="size-8 mx-auto text-gray-400 mb-3" />
                        <p class="text-secondary">Aucun emploi du temps n'a encore été configuré pour votre classe.</p>
                    </flux:card>
                @else
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        @foreach($this->getJoursSemaine() as $jourId => $jourNom)
                            @if($seancesParJour->has($jourNom))
                                <flux:card class="p-0 overflow-hidden">
                                    <div class="bg-surface-container-low px-4 py-3 border-b border-surface-variant flex items-center justify-between">
                                        <h3 class="font-semibold text-primary text-lg">{{ $jourNom }}</h3>
                                        @php
                                            $isToday = \Carbon\Carbon::now()->dayOfWeekIso == $jourId;
                                        @endphp
                                        @if($isToday)
                                            <flux:badge color="blue">Aujourd'hui</flux:badge>
                                        @endif
                                    </div>
                                    <div class="divide-y divide-surface-variant">
                                        @foreach($seancesParJour[$jourNom] as $seance)
                                            <div class="p-4 flex items-start gap-4 hover:bg-surface-container-lowest/50 transition-colors {{ $isToday ? 'bg-blue-50/30' : '' }}">
                                                <div class="w-20 flex flex-col text-right pt-1 border-r-2 border-outline-variant pr-4">
                                                    <span class="font-bold text-primary">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}</span>
                                                    <span class="text-xs text-secondary">{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</span>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-primary">{{ $seance->cours->matiere->nom }}</h4>
                                                    <p class="text-sm text-secondary mt-1 flex items-center gap-2">
                                                        <flux:icon.user class="size-4" />
                                                        {{ $seance->cours->enseignant->user->prenom ?? '' }} {{ $seance->cours->enseignant->user->nom ?? '' }}
                                                    </p>
                                                    <p class="text-sm text-secondary mt-1 flex items-center gap-2">
                                                        <flux:icon.map-pin class="size-4" />
                                                        {{ $seance->salle->nom ?? 'Salle non définie' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </flux:card>
                            @endif
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                    <p class="text-secondary">Vous n'avez aucune inscription active cette année.</p>
                </div>
            @endif
        </div>
    </div>
</div>
