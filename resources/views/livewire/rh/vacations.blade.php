<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Vacations Enseignants</h2>
                    <p class="font-body-lg text-secondary mt-1">Suivi des heures de vacation, calcul des montants dus et paiements.</p>
                </div>

                <flux:modal.trigger name="vacation-modal">
                    <flux:button variant="primary" icon="plus">Enregistrer une vacation</flux:button>
                </flux:modal.trigger>
            </header>

            @if(session('status'))
                <flux:badge color="green" class="p-3 mb-4 w-full">
                    {{ session('status') }}
                </flux:badge>
            @endif

            <!-- KPIs Header Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <flux:card class="p-5 text-center">
                    <span class="text-xs text-secondary uppercase font-bold">Total Heures</span>
                    <div class="text-2xl font-bold text-primary mt-2">{{ number_format($this->stats['total_heures'], 1, ',', ' ') }} h</div>
                </flux:card>
                <flux:card class="p-5 text-center">
                    <span class="text-xs text-secondary uppercase font-bold">Budget Total</span>
                    <div class="text-2xl font-bold text-primary mt-2">{{ number_format($this->stats['total_montant'], 0, ',', ' ') }} FCFA</div>
                </flux:card>
                <flux:card class="p-5 text-center border-green-100 bg-green-50/20">
                    <span class="text-xs text-green-700 uppercase font-bold">Montant Régler (Payé)</span>
                    <div class="text-2xl font-bold text-green-600 mt-2">{{ number_format($this->stats['montant_paye'], 0, ',', ' ') }} FCFA</div>
                </flux:card>
                <flux:card class="p-5 text-center border-orange-100 bg-orange-50/20">
                    <span class="text-xs text-orange-700 uppercase font-bold">En Attente de Paiement</span>
                    <div class="text-2xl font-bold text-orange-600 mt-2">{{ number_format($this->stats['montant_attente'], 0, ',', ' ') }} FCFA</div>
                </flux:card>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-xl border border-surface-variant">
                <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    <select wire:model.live="filterPersonnel" class="p-2 border border-surface-variant rounded-lg text-sm bg-surface-container-lowest text-primary">
                        <option value="">Tous les enseignants</option>
                        @foreach($this->personnels as $p)
                            <option value="{{ $p->id }}">{{ $p->user->nom ?? '' }} {{ $p->user->prenom ?? '' }} ({{ $p->matricule }})</option>
                        @endforeach
                    </select>

                    <select wire:model.live="filterStatus" class="p-2 border border-surface-variant rounded-lg text-sm bg-surface-container-lowest text-primary">
                        <option value="">Tous les statuts</option>
                        <option value="En attente">En attente</option>
                        <option value="Payé">Payé</option>
                        <option value="Annulé">Annulé</option>
                    </select>
                </div>
            </div>

            <!-- Vacations Table -->
            @if($this->vacations->isEmpty())
                <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                    <flux:icon.banknotes class="size-8 mx-auto text-gray-400 mb-3" />
                    <p class="text-secondary">Aucune fiche de vacation enregistrée pour le moment.</p>
                </flux:card>
            @else
                <flux:card class="p-0 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Enseignant</th>
                                    <th class="px-6 py-3">Matière & Classe</th>
                                    <th class="px-6 py-3 text-center">Durée</th>
                                    <th class="px-6 py-3 text-right">Montant</th>
                                    <th class="px-6 py-3 text-center">Statut</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-variant bg-white">
                                @foreach($this->vacations as $v)
                                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-primary whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($v->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-primary">
                                            {{ $v->personnel->user->nom ?? '' }} {{ $v->personnel->user->prenom ?? '' }}
                                            <span class="block text-xs font-normal text-secondary">{{ $v->personnel->matricule }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-secondary">
                                            <span class="font-medium text-primary">{{ $v->cours->matiere->nom ?? 'N/A' }}</span>
                                            <span class="block text-xs">{{ $v->cours->classe->nom ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-primary">
                                            {{ $v->nombre_heures }} h
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-primary">
                                            {{ number_format($v->montant, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($v->etat === 'Payé')
                                                <flux:badge color="green">Payé</flux:badge>
                                            @elseif($v->etat === 'En attente')
                                                <flux:badge color="orange">En attente</flux:badge>
                                            @else
                                                <flux:badge color="zinc">Annulé</flux:badge>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($v->etat === 'En attente')
                                                    <flux:button size="xs" variant="primary" color="green" wire:click="markAsPaid({{ $v->id }})">
                                                        Valider Paiement
                                                    </flux:button>
                                                @endif
                                                <flux:button size="xs" variant="ghost" color="red" wire:click="delete({{ $v->id }})">
                                                    Supprimer
                                                </flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>

    <!-- Create Vacation Modal -->
    <flux:modal name="vacation-modal" class="md:w-[500px]">
        <form wire:submit="save" class="space-y-4">
            <div>
                <flux:heading size="lg">Nouvelle Fiche de Vacation</flux:heading>
                <flux:subheading>Saisissez les heures de cours effectuées par l'enseignant.</flux:subheading>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-primary mb-1">Enseignant</label>
                    <select wire:model.live="personnel_id" class="w-full p-2 border border-surface-variant rounded-lg text-sm bg-white">
                        <option value="">Sélectionner un enseignant...</option>
                        @foreach($this->personnels as $p)
                            <option value="{{ $p->id }}">{{ $p->user->nom ?? '' }} {{ $p->user->prenom ?? '' }} ({{ $p->matricule }})</option>
                        @endforeach
                    </select>
                    @error('personnel_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-primary mb-1">Cours / Classe</label>
                    <select wire:model="cours_id" class="w-full p-2 border border-surface-variant rounded-lg text-sm bg-white" @if(!$this->personnel_id) disabled @endif>
                        <option value="">Sélectionner le cours...</option>
                        @foreach($this->cours as $c)
                            <option value="{{ $c->id }}">{{ $c->matiere->nom ?? '' }} ({{ $c->classe->nom ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('cours_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-primary mb-1">Date</label>
                        <input type="date" wire:model="date" class="w-full p-2 border border-surface-variant rounded-lg text-sm" />
                        @error('date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-primary mb-1">Durée (heures)</label>
                        <input type="number" step="0.5" wire:model="nombre_heures" class="w-full p-2 border border-surface-variant rounded-lg text-sm" />
                        @error('nombre_heures') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-primary mb-1">Montant total (FCFA)</label>
                        <input type="number" wire:model="montant" placeholder="ex: 10000" class="w-full p-2 border border-surface-variant rounded-lg text-sm font-bold" />
                        @error('montant') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-primary mb-1">Statut</label>
                        <select wire:model="etat" class="w-full p-2 border border-surface-variant rounded-lg text-sm bg-white">
                            <option value="En attente">En attente</option>
                            <option value="Payé">Payé</option>
                        </select>
                        @error('etat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Annuler</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">Enregistrer</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
