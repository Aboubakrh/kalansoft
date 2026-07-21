<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Child Selector -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Dossiers Scolaires</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez les informations administratives et médicales de votre enfant.</p>
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Identité -->
                    <flux:card class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                                <flux:icon.user class="size-6" />
                            </div>
                            <h3 class="text-lg font-semibold text-primary">Identité de l'élève</h3>
                        </div>
                        
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Matricule</dt>
                                <dd class="mt-1 text-sm text-primary font-semibold">{{ $this->selectedEleve->matricule }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Sexe</dt>
                                <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->sexe == 'M' ? 'Masculin' : 'Féminin' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Nom complet</dt>
                                <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->user?->prenom }} {{ $this->selectedEleve->user?->nom }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Nationalité</dt>
                                <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->nationalite ?? 'Non renseignée' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Date de naissance</dt>
                                <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->date_naissance ? \Carbon\Carbon::parse($this->selectedEleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Lieu de naissance</dt>
                                <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->lieu_naissance ?? 'Non renseigné' }}</dd>
                            </div>
                        </dl>
                    </flux:card>

                    <!-- Informations Médicales & Urgence -->
                    <div class="flex flex-col gap-6">
                        <flux:card class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                                    <flux:icon.heart class="size-6" />
                                </div>
                                <h3 class="text-lg font-semibold text-primary">Informations Médicales</h3>
                            </div>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Groupe Sanguin</dt>
                                    <dd class="mt-1 text-sm text-primary font-semibold text-red-600">{{ $this->selectedEleve->groupe_sanguin ?? 'Non renseigné' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Antécédents / Allergies</dt>
                                    <dd class="mt-1 text-sm text-primary">
                                        @if($this->selectedEleve->antecedents_medicaux)
                                            <p class="p-3 bg-orange-50 border border-orange-100 text-orange-800 rounded-lg">{{ $this->selectedEleve->antecedents_medicaux }}</p>
                                        @else
                                            <span class="text-secondary italic">Aucun antécédent signalé.</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </flux:card>

                        <flux:card class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
                                    <flux:icon.phone class="size-6" />
                                </div>
                                <h3 class="text-lg font-semibold text-primary">Contact d'urgence (Tuteur)</h3>
                            </div>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                <div>
                                    <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Nom du tuteur</dt>
                                    <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->nom_tuteur ?? 'Non renseigné' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Téléphone</dt>
                                    <dd class="mt-1 text-sm text-primary font-semibold">{{ $this->selectedEleve->telephone_tuteur ?? 'Non renseigné' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs font-medium text-secondary uppercase tracking-wider">Adresse</dt>
                                    <dd class="mt-1 text-sm text-primary">{{ $this->selectedEleve->adresse_tuteur ?? 'Non renseignée' }}</dd>
                                </div>
                            </dl>
                        </flux:card>
                    </div>

                    <!-- Documents -->
                    <flux:card class="p-6 lg:col-span-2">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                                <flux:icon.document-text class="size-6" />
                            </div>
                            <h3 class="text-lg font-semibold text-primary">Documents Numérisés</h3>
                        </div>

                        @if($this->selectedEleve->documents->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-secondary">
                                    <thead class="text-xs uppercase bg-surface border-b border-surface-variant">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Type</th>
                                            <th scope="col" class="px-4 py-3">Numéro</th>
                                            <th scope="col" class="px-4 py-3">Date</th>
                                            <th scope="col" class="px-4 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($this->selectedEleve->documents as $doc)
                                            <tr class="border-b border-surface-variant hover:bg-surface/50">
                                                <td class="px-4 py-3 font-medium text-primary">{{ $doc->type_document }}</td>
                                                <td class="px-4 py-3">{{ $doc->numero ?? '-' }}</td>
                                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($doc->date_generation)->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <flux:button size="sm" variant="subtle" icon="arrow-down-tray" href="{{ asset('storage/' . $doc->fichier) }}" target="_blank">Télécharger</flux:button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-surface rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                                    <flux:icon.folder-open class="size-6 text-secondary" />
                                </div>
                                <p class="text-secondary text-sm">Aucun document numérique n'est associé à ce dossier.</p>
                            </div>
                        @endif
                    </flux:card>
                </div>
            @else
                <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                    <p class="text-secondary">Aucun enfant n'est lié à votre compte actuellement.</p>
                </div>
            @endif
        </div>
    </div>
</div>
