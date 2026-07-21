<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Child Selector -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Scolarité & Paiements</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez l'historique et effectuez vos paiements en ligne.</p>
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

            @if (session()->has('success'))
                <flux:card class="p-4 bg-green-50 border-green-200">
                    <div class="flex items-center gap-3 text-green-800">
                        <flux:icon.check-circle class="size-6" />
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </flux:card>
            @endif

            @if($this->selectedEleve)
                @if($this->currentInscription)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Situation Financière -->
                        <div class="lg:col-span-1 space-y-6">
                            <flux:card class="p-6">
                                <h3 class="text-lg font-semibold text-primary mb-4">Situation Financière</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-secondary">Frais Totaux (Scolarité)</p>
                                        <p class="text-xl font-bold text-primary">{{ number_format($this->currentInscription->frais_scolarite, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div class="border-t border-surface-variant pt-4">
                                        <p class="text-sm text-secondary">Total Payé</p>
                                        <p class="text-xl font-bold text-green-600">{{ number_format($this->currentInscription->totalPaye(), 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div class="border-t border-surface-variant pt-4">
                                        <p class="text-sm text-secondary">Reste à Payer</p>
                                        @php
                                            $reste = $this->currentInscription->soldeRestant();
                                        @endphp
                                        <p class="text-2xl font-bold {{ $reste > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($reste, 0, ',', ' ') }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </flux:card>

                            <!-- Formulaire de Paiement Simulé -->
                            @if($reste > 0)
                            <flux:card class="p-6 border-blue-200 bg-blue-50/30">
                                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center gap-2">
                                    <flux:icon.credit-card class="size-5 text-blue-600" />
                                    Paiement en ligne (Simulation)
                                </h3>
                                
                                <form wire:submit="traiterPaiement" class="space-y-4">
                                    <flux:input wire:model="montant" type="number" label="Montant à payer (FCFA)" placeholder="Ex: 50000" min="100" />
                                    
                                    <flux:radio.group wire:model="mode_paiement" label="Mode de paiement">
                                        <flux:radio value="Orange Money" label="Orange Money" />
                                        <flux:radio value="Moov Money" label="Moov Money" />
                                        <flux:radio value="Carte Bancaire" label="Carte Bancaire" />
                                    </flux:radio.group>

                                    <flux:input wire:model="telephone_ou_carte" type="text" label="N° de Téléphone / Numéro de Carte" placeholder="Saisissez votre numéro" />
                                    
                                    <flux:button type="submit" variant="primary" class="w-full mt-2">
                                        Confirmer le paiement
                                    </flux:button>
                                </form>
                            </flux:card>
                            @else
                            <flux:card class="p-6 bg-green-50 border-green-200 text-center">
                                <div class="inline-flex items-center justify-center p-3 bg-green-100 rounded-full mb-3 text-green-600">
                                    <flux:icon.check class="size-8" />
                                </div>
                                <h3 class="text-lg font-semibold text-green-800">Scolarité Soldée</h3>
                                <p class="text-sm text-green-700 mt-1">Vous avez réglé la totalité des frais pour cette année scolaire.</p>
                            </flux:card>
                            @endif
                        </div>

                        <!-- Historique des Paiements -->
                        <div class="lg:col-span-2">
                            <flux:card class="p-6 h-full">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-3 bg-gray-100 text-gray-600 rounded-lg">
                                        <flux:icon.clock class="size-6" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-primary">Historique des Paiements</h3>
                                </div>

                                @if($this->currentInscription->paiements->isNotEmpty())
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-secondary">
                                            <thead class="text-xs uppercase bg-surface border-b border-surface-variant">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3">Date</th>
                                                    <th scope="col" class="px-4 py-3">N° Reçu</th>
                                                    <th scope="col" class="px-4 py-3">Tranche</th>
                                                    <th scope="col" class="px-4 py-3">Mode</th>
                                                    <th scope="col" class="px-4 py-3 text-right">Montant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($this->currentInscription->paiements->sortByDesc('created_at') as $paiement)
                                                    <tr class="border-b border-surface-variant hover:bg-surface/50">
                                                        <td class="px-4 py-3 font-medium text-primary">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</td>
                                                        <td class="px-4 py-3 text-xs font-mono bg-gray-50 rounded px-1">{{ $paiement->numero_recu }}</td>
                                                        <td class="px-4 py-3">{{ $paiement->intitule_tranche }}</td>
                                                        <td class="px-4 py-3">{{ $paiement->mode_paiement }}</td>
                                                        <td class="px-4 py-3 text-right font-bold text-green-600">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <div class="bg-surface rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                                            <flux:icon.banknotes class="size-6 text-secondary" />
                                        </div>
                                        <p class="text-secondary text-sm">Aucun paiement n'a encore été effectué pour cette année scolaire.</p>
                                    </div>
                                @endif
                            </flux:card>
                        </div>
                    </div>
                @else
                    <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                        <p class="text-secondary">Aucune inscription active pour cet enfant, impossible d'afficher la situation financière.</p>
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
