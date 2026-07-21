<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Child Selector -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Emploi du Temps</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez le planning hebdomadaire de votre enfant.</p>
                </div>
                
                @if($this->eleves->isNotEmpty())
                <flux:dropdown>
                    <flux:button icon-trailing="chevron-down" class="w-full md:w-auto text-left">
                        Sélectionner un enfant : <strong class="ml-1">{{ $this->selectedEleve ? $this->selectedEleve->user?->prenom : 'Aucun' }}</strong>
                    </flux:button>
                    <flux:menu>
                        @foreach($this->eleves as $eleve)
                            <flux:menu.item wire:click="selectEleve({{ $eleve->id }})">
                                {{ $eleve->user?->prenom }} {{ $eleve->user?->nom }} 
                                @if($eleve->inscriptions->first())
                                    ({{ $eleve->inscriptions->first()->classe->nom }})
                                @endif
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>
                @endif
            </header>

            @if($this->selectedEleve)
                @if($this->currentInscription)
                    <!-- Weekly Schedule Grid -->
                    <flux:card class="p-6 overflow-x-auto">
                        <div class="min-w-[800px]">
                            <!-- Grid Header -->
                            <div class="grid gap-2 mb-4 border-b border-surface-variant pb-4" style="grid-template-columns: 80px repeat(6, 1fr);">
                                <div class="text-xs font-semibold text-secondary flex items-end justify-center uppercase tracking-wider">Heure</div>
                                <div class="text-sm font-semibold text-center text-primary">Lundi</div>
                                <div class="text-sm font-semibold text-center text-primary">Mardi</div>
                                <div class="text-sm font-semibold text-center text-primary">Mercredi</div>
                                <div class="text-sm font-semibold text-center text-primary">Jeudi</div>
                                <div class="text-sm font-semibold text-center text-primary">Vendredi</div>
                                <div class="text-sm font-semibold text-center text-primary">Samedi</div>
                            </div>

                            <!-- Grid Body -->
                            <div class="relative grid gap-2" style="grid-template-columns: 80px repeat(6, 1fr); grid-template-rows: repeat(10, minmax(60px, auto));">
                                <!-- Time Labels -->
                                @for($i=8; $i<=17; $i++)
                                    <div class="col-start-1 text-xs text-secondary text-center pr-2 py-2 border-r border-surface-variant" style="grid-row-start: {{ $i-7 }};">
                                        {{ str_pad((string)$i, 2, '0', STR_PAD_LEFT) }}:00
                                    </div>
                                    <!-- Horizontal Grid Lines -->
                                    <div class="col-start-2 col-end-8 border-t border-surface-variant/50 h-full pointer-events-none @if($i==12) bg-surface-variant/20 @endif" style="grid-row-start: {{ $i-7 }};"></div>
                                @endfor

                                <!-- Course Blocks -->
                                @php
                                    $joursMap = ['Lundi'=>2, 'Mardi'=>3, 'Mercredi'=>4, 'Jeudi'=>5, 'Vendredi'=>6, 'Samedi'=>7];
                                    $colors = [
                                        'Mathématiques' => 'bg-blue-50 border-blue-200 text-blue-800',
                                        'Physique-Chimie' => 'bg-purple-50 border-purple-200 text-purple-800',
                                        'SVT' => 'bg-green-50 border-green-200 text-green-800',
                                        'Histoire-Géo' => 'bg-orange-50 border-orange-200 text-orange-800',
                                        'Français' => 'bg-red-50 border-red-200 text-red-800',
                                        'Anglais' => 'bg-teal-50 border-teal-200 text-teal-800',
                                    ];
                                    $defaultColor = 'bg-gray-50 border-gray-200 text-gray-800';
                                @endphp

                                @foreach($this->emplois as $emploi)
                                    @php
                                        $colStart = $joursMap[$emploi->jour] ?? 2;
                                        $hDebut = (int) substr((string)$emploi->heure_debut, 0, 2);
                                        $hFin = (int) substr((string)$emploi->heure_fin, 0, 2);
                                        $rowStart = max(1, $hDebut - 7);
                                        $rowSpan = max(1, $hFin - $hDebut);
                                        $matiereNom = $emploi->cours->matiere->nom ?? 'Inconnu';
                                        $enseignantNom = $emploi->cours->enseignant->user->name ?? '';
                                        $colorClass = $colors[$matiereNom] ?? $defaultColor;
                                    @endphp
                                    <div class="course-block relative {{ $colorClass }} rounded-xl p-3 shadow-sm transition-all cursor-default" style="grid-column-start: {{ $colStart }}; grid-row: {{ $rowStart }} / span {{ $rowSpan }};">
                                        <div class="font-bold text-sm mb-1">{{ $matiereNom }}</div>
                                        <div class="text-xs font-medium opacity-90 mb-1 flex items-center gap-1">
                                            <flux:icon.user class="size-3" /> {{ $enseignantNom }}
                                        </div>
                                        <div class="text-xs opacity-75 mt-0.5 flex items-center gap-1">
                                            <flux:icon.map-pin class="size-3" /> {{ $emploi->salle->nom ?? '' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </flux:card>
                @else
                    <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                        <p class="text-secondary">Aucune inscription active pour cet enfant, impossible de charger l'emploi du temps.</p>
                    </div>
                @endif
            @else
                <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                    <p class="text-secondary">Aucun enfant n'est lié à votre compte actuellement.</p>
                </div>
            @endif
        </div>
    </div>
</div>
