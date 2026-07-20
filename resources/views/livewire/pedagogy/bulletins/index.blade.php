<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Page Header -->
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Génération des Bulletins</h2>
                    <p class="font-body-lg text-secondary mt-1">Calculez les moyennes et générez les bulletins trimestriels ou semestriels.</p>
                </div>
            </div>

            @if(session('status'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <flux:card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <flux:select wire:model.live="classe_id" label="Sélectionner une classe">
                        <option value="">-- Choisir une classe --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="periode" label="Période (Trimestre / Semestre)">
                        <option value="Trimestre 1">Trimestre 1</option>
                        <option value="Trimestre 2">Trimestre 2</option>
                        <option value="Trimestre 3">Trimestre 3</option>
                        <option value="Semestre 1">Semestre 1</option>
                        <option value="Semestre 2">Semestre 2</option>
                    </flux:select>
                </div>

                @if(!empty($classe_id))
                    @if(count($inscriptions) > 0)
                        <div class="overflow-x-auto">
                            <flux:table>
                                <flux:table.columns>
                                    <flux:table.column>Matricule</flux:table.column>
                                    <flux:table.column>Prénom & Nom</flux:table.column>
                                    <flux:table.column>Moyenne (Aperçu)</flux:table.column>
                                    <flux:table.column>Actions</flux:table.column>
                                </flux:table.columns>
                                
                                <flux:table.rows>
                                    @foreach($inscriptions as $insc)
                                    <flux:table.row>
                                        <flux:table.cell>{{ $insc->eleve->matricule }}</flux:table.cell>
                                        <flux:table.cell>{{ $insc->eleve->user->prenom }} {{ $insc->eleve->user->nom }}</flux:table.cell>
                                        <flux:table.cell>
                                            <span class="bg-surface-variant/30 text-on-surface px-2 py-1 rounded text-xs font-semibold">
                                                En attente de calcul
                                            </span>
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <flux:button variant="ghost" size="sm" icon="document-arrow-down" wire:click="generateBulletin({{ $insc->id }})">Générer</flux:button>
                                        </flux:table.cell>
                                    </flux:table.row>
                                    @endforeach
                                </flux:table.rows>
                            </flux:table>
                        </div>
                    @else
                        <div class="text-center py-12 text-secondary">
                            <flux:icon.users class="size-12 mx-auto mb-4 opacity-50" />
                            <p class="text-lg font-medium">Aucun élève inscrit dans cette classe</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 text-secondary border-t border-surface-variant/50 mt-6">
                        <flux:icon.academic-cap class="size-12 mx-auto mb-4 opacity-50" />
                        <p class="text-lg font-medium">Veuillez sélectionner une classe pour voir les élèves</p>
                    </div>
                @endif
            </flux:card>
        </div>
    </div>
</div>
