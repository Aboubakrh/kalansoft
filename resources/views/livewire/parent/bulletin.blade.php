<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Child Selector -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Bulletins & Notes</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez les résultats scolaires de votre enfant.</p>
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
                    
                    <!-- Section Bulletins -->
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                                <flux:icon.document-text class="size-6" />
                            </div>
                            <h3 class="text-xl font-semibold text-primary">Bulletins Périodiques</h3>
                        </div>

                        @if($this->periodes->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($this->periodes as $periode)
                                    <flux:card class="p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                                        <div>
                                            <div class="flex justify-between items-start mb-4">
                                                <h4 class="text-lg font-semibold text-primary">{{ $periode }}</h4>
                                                <flux:badge color="green">Disponible</flux:badge>
                                            </div>
                                            <p class="text-sm text-secondary mb-6">Téléchargez le bulletin officiel au format PDF incluant le rang et les appréciations.</p>
                                        </div>
                                        <flux:button variant="primary" icon="arrow-down-tray" class="w-full" href="{{ route('parent.bulletins.download', ['inscription' => $this->currentInscription->id, 'periode' => $periode]) }}" target="_blank">
                                            Télécharger
                                        </flux:button>
                                    </flux:card>
                                @endforeach
                            </div>
                        @else
                            <flux:card class="p-8 text-center bg-gray-50 border-gray-200">
                                <flux:icon.document-minus class="size-8 mx-auto text-gray-400 mb-3" />
                                <p class="text-secondary">Aucun bulletin n'est encore disponible pour cette année scolaire.</p>
                            </flux:card>
                        @endif
                    </div>

                    <!-- Section Relevé de Notes -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                                <flux:icon.academic-cap class="size-6" />
                            </div>
                            <h3 class="text-xl font-semibold text-primary">Relevé de Notes Récent</h3>
                        </div>

                        @if($this->notes->isNotEmpty())
                            @php
                                // Regrouper les notes par période pour un affichage plus clair
                                $groupedNotes = $this->notes->groupBy(function($note) {
                                    return $note->evaluation->periode ?? 'Autre';
                                });
                            @endphp

                            <div class="space-y-8">
                                @foreach($groupedNotes as $periode => $notesPeriode)
                                    <flux:card class="p-0 overflow-hidden">
                                        <div class="bg-surface-container-low px-6 py-4 border-b border-surface-variant flex items-center justify-between">
                                            <h4 class="font-semibold text-primary">{{ $periode }}</h4>
                                            <span class="text-xs text-secondary font-medium">{{ $notesPeriode->count() }} note(s)</span>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-sm">
                                                <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary font-label-caps text-xs uppercase">
                                                    <tr>
                                                        <th class="px-6 py-3">Date</th>
                                                        <th class="px-6 py-3">Matière</th>
                                                        <th class="px-6 py-3">Évaluation</th>
                                                        <th class="px-6 py-3">Note</th>
                                                        <th class="px-6 py-3">Observation</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-surface-variant bg-white">
                                                    @foreach($notesPeriode as $note)
                                                        <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                                            <td class="px-6 py-4 text-secondary whitespace-nowrap">
                                                                {{ \Carbon\Carbon::parse($note->evaluation->date_evaluation)->format('d/m/Y') }}
                                                            </td>
                                                            <td class="px-6 py-4 font-medium text-primary">
                                                                {{ $note->evaluation->cours->matiere->nom ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-6 py-4 text-secondary">
                                                                {{ $note->evaluation->type_evaluation }}
                                                                @if($note->evaluation->coefficient > 1)
                                                                    <flux:badge size="sm" color="zinc" class="ml-2">Coef {{ (int)$note->evaluation->coefficient }}</flux:badge>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                @php
                                                                    $ratio = $note->valeur / $note->evaluation->bareme;
                                                                    $colorClass = $ratio >= 0.5 ? 'text-green-600' : 'text-red-600';
                                                                @endphp
                                                                <div class="flex items-baseline gap-1">
                                                                    <span class="font-bold text-lg {{ $colorClass }}">{{ rtrim(rtrim(number_format($note->valeur, 2, ',', ''), '0'), ',') }}</span>
                                                                    <span class="text-xs text-secondary">/ {{ rtrim(rtrim(number_format($note->evaluation->bareme, 2, ',', ''), '0'), ',') }}</span>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 text-secondary italic text-xs">
                                                                {{ $note->observation ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </flux:card>
                                @endforeach
                            </div>
                        @else
                            <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                                <flux:icon.pencil-square class="size-8 mx-auto text-gray-400 mb-3" />
                                <p class="text-secondary">Aucune note n'a été saisie pour cet enfant pour le moment.</p>
                            </flux:card>
                        @endif
                    </div>
                @else
                    <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                        <p class="text-secondary">Aucune inscription active pour cet enfant, impossible d'afficher les résultats.</p>
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
