<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Caisse - Nouvel Encaissement</h2>
                <p class="font-body-lg text-secondary mt-1">Enregistrer un paiement de scolarité</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.finances.paiements') }}">Retour aux paiements</flux:button>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Étape 1 : Sélection de l'élève -->
            <div class="lg:col-span-1 space-y-6">
                <flux:card class="p-6">
                    <flux:heading size="lg" class="mb-4">1. Sélection de l'élève</flux:heading>
                    
                    @if(!$selectedInscription)
                        <div class="relative">
                            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher nom, matricule..." />
                            
                            @if(count($this->inscriptions) > 0)
                                <div class="absolute z-10 w-full mt-1 bg-surface rounded-md shadow-lg border border-outline">
                                    <ul class="max-h-60 overflow-auto py-1">
                                        @foreach($this->inscriptions as $insc)
                                            <li wire:click="selectInscription({{ $insc->id }})" class="px-4 py-2 hover:bg-surface-container-low cursor-pointer flex justify-between items-center">
                                                <div>
                                                    <div class="font-medium text-primary">{{ $insc->eleve->user->name }}</div>
                                                    <div class="text-xs text-secondary">{{ $insc->eleve->matricule }} - {{ $insc->classe->nom ?? 'N/A' }}</div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif(strlen($search) > 2)
                                <div class="absolute z-10 w-full mt-1 bg-surface rounded-md shadow-lg border border-outline p-4 text-center text-secondary">
                                    Aucun élève trouvé.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex justify-between items-start border border-primary/20 bg-primary/5 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                @if($selectedInscription->eleve->user->photo)
                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $selectedInscription->eleve->user->photo) }}" alt="Photo"/>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm">
                                        {{ $selectedInscription->eleve->user->initials() }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-primary">{{ $selectedInscription->eleve->user->name }}</div>
                                    <div class="text-sm text-secondary">{{ $selectedInscription->classe->nom ?? 'Sans classe' }}</div>
                                </div>
                            </div>
                            <flux:button variant="ghost" size="sm" icon="x-mark" wire:click="cancelSelection" />
                        </div>
                    @endif
                </flux:card>

                @if($selectedInscription)
                <flux:card class="p-6">
                    <flux:heading size="lg" class="mb-4">Statut Financier</flux:heading>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-secondary">Scolarité Totale</span>
                            <span class="font-medium">{{ number_format($selectedInscription->frais_scolarite, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-success">
                            <span>Déjà Payé</span>
                            <span class="font-medium">{{ number_format($selectedInscription->totalPaye(), 0, ',', ' ') }} FCFA</span>
                        </div>
                        <flux:separator />
                        <div class="flex justify-between text-lg">
                            <span class="font-bold text-primary">Reste à payer</span>
                            <span class="font-bold text-danger">{{ number_format($selectedInscription->soldeRestant(), 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </flux:card>
                @endif
            </div>

            <!-- Étape 2 : Encaissement -->
            <div class="lg:col-span-2">
                <flux:card class="p-6">
                    <flux:heading size="lg" class="mb-6">2. Détails du Paiement</flux:heading>

                    @if(!$selectedInscription)
                        <div class="flex flex-col items-center justify-center py-12 text-center bg-surface-container-lowest rounded-xl border border-dashed border-outline-variant">
                            <div class="w-16 h-16 bg-surface-container-low rounded-full flex items-center justify-center text-secondary mb-4">
                                <flux:icon.user class="size-8" />
                            </div>
                            <h3 class="font-title-md text-primary mb-2">Aucun élève sélectionné</h3>
                            <p class="font-body-sm text-secondary max-w-sm">Recherchez et sélectionnez un élève dans la section de gauche pour pouvoir enregistrer un paiement.</p>
                        </div>
                    @else

                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <flux:input wire:model="montant" type="number" label="Montant à encaisser (FCFA)" required />
                            <flux:select wire:model="mode_paiement" label="Mode de paiement" required>
                                <option value="Espèces">Espèces</option>
                                <option value="Chèque">Chèque</option>
                                <option value="Mobile Money">Mobile Money (Orange/Moov/Wave)</option>
                                <option value="Virement Bancaire">Virement Bancaire</option>
                            </flux:select>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <flux:select wire:model="intitule_tranche" label="Intitulé de la tranche" required>
                                <option value="Inscription">Frais d'Inscription</option>
                                <option value="Tranche 1">Tranche 1 (1er Trimestre)</option>
                                <option value="Tranche 2">Tranche 2 (2ème Trimestre)</option>
                                <option value="Tranche 3">Tranche 3 (3ème Trimestre)</option>
                                <option value="Scolarité Intégrale">Scolarité Intégrale</option>
                            </flux:select>
                            <flux:input wire:model="observation" label="Observation / Réf. (Optionnel)" />
                        </div>

                        @if($selectedInscription && $selectedInscription->soldeRestant() == 0)
                            <div class="p-4 bg-success/10 text-success rounded-lg flex items-center gap-2">
                                <flux:icon.check-circle class="size-5" />
                                <span class="font-medium">La scolarité de cet élève est entièrement réglée.</span>
                            </div>
                        @else
                            <div class="flex justify-end mt-8">
                                <flux:button type="submit" variant="primary" icon="banknotes" class="w-full sm:w-auto">
                                    Valider l'Encaissement
                                </flux:button>
                            </div>
                        @endif
                    </form>
                    @endif
                </flux:card>
            </div>
            
        </div>
    </div>
</div>
