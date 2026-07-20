<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <!-- Page Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-headline-lg-mobile md:font-headline-lg text-[24px] text-primary">Ressources Humaines & Corps Enseignant</h2>
                <p class="font-body-sm text-secondary mt-1">Gestion administrative et financière du personnel éducatif.</p>
            </div>
            <flux:modal.trigger name="personnel-modal">
                <flux:button variant="primary" icon="plus" class="w-full md:w-auto" wire:click="create">
                    Ajouter un membre
                </flux:button>
            </flux:modal.trigger>
        </header>

        <!-- KPI Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <flux:card class="p-6 flex flex-col justify-between h-32 relative overflow-hidden bg-surface-container-lowest">
                <div class="flex items-center justify-between z-10">
                    <p class="font-body-sm text-secondary font-medium">Total Personnel</p>
                    <flux:icon.users class="size-5 text-secondary" />
                </div>
                <p class="text-4xl font-bold text-primary z-10">{{ $totalPersonnel }}</p>
                <div class="absolute -bottom-4 -right-4 opacity-5 z-0">
                    <flux:icon.users class="size-32" />
                </div>
            </flux:card>

            <flux:card class="p-6 flex flex-col justify-between h-32 relative overflow-hidden bg-surface-container-lowest">
                <div class="flex items-center justify-between z-10">
                    <p class="font-body-sm text-secondary font-medium">Titulaires</p>
                    <flux:icon.identification class="size-5 text-secondary" />
                </div>
                <p class="text-4xl font-bold text-primary z-10">{{ $titulaires }}</p>
            </flux:card>

            <flux:card class="p-6 flex flex-col justify-between h-32 relative overflow-hidden bg-surface-container-lowest">
                <div class="flex items-center justify-between z-10">
                    <p class="font-body-sm text-secondary font-medium">Vacataires</p>
                    <flux:icon.clock class="size-5 text-secondary" />
                </div>
                <p class="text-4xl font-bold text-primary z-10">{{ $vacataires }}</p>
            </flux:card>
        </div>

        <!-- Content Area Tabs -->
        <div class="flex border-b border-outline-variant mb-6">
            <button class="px-4 py-2 border-b-2 border-primary text-primary font-medium text-sm">Liste du Personnel</button>
            <button class="px-4 py-2 border-b-2 border-transparent text-secondary hover:text-primary font-medium text-sm">Paiement des Vacations</button>
        </div>

        <!-- Bento Box Layout for Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Main Directory Table Card -->
            <flux:card class="lg:col-span-8 p-0 overflow-hidden flex flex-col shadow-sm">
                <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <h3 class="font-title-md text-lg text-primary font-semibold">Annuaire</h3>
                    <div class="flex gap-2">
                        <flux:button variant="ghost" size="sm" icon="funnel"></flux:button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface-container-low/50 border-b border-outline-variant">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-secondary whitespace-nowrap">Matricule</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Employé</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Fonction</th>
                                <th class="px-6 py-4 font-semibold text-secondary">Statut</th>
                                <th class="px-6 py-4 font-semibold text-secondary text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline">
                            @forelse($personnels as $personnel)
                                <tr class="hover:bg-surface-container-low/50 transition-colors group">
                                    <td class="px-6 py-4 text-secondary font-mono text-sm">{{ $personnel->matricule }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($personnel->user->photo)
                                                <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . $personnel->user->photo) }}" alt="Photo"/>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                                                    {{ $personnel->user->initials() }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-primary">{{ $personnel->user->name }}</p>
                                                <p class="text-secondary text-xs">{{ $personnel->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-primary">{{ $personnel->fonction }}</td>
                                    <td class="px-6 py-4">
                                        @if($personnel->type_personnel === 'Titulaire')
                                            <flux:badge color="zinc">{{ $personnel->type_personnel }}</flux:badge>
                                        @else
                                            <flux:badge color="primary">{{ $personnel->type_personnel ?? 'Non défini' }}</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <flux:modal.trigger name="personnel-modal">
                                                <flux:button variant="ghost" size="sm" icon="pencil" wire:click="edit({{ $personnel->id }})" class="text-secondary hover:text-primary" />
                                            </flux:modal.trigger>
                                            <flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $personnel->id }})" wire:confirm="Confirmez-vous la suppression ?" class="text-secondary hover:text-danger" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-secondary">
                                        Aucun membre du personnel enregistré.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </flux:card>

            <!-- Modal Création/Édition -->
            <flux:modal name="personnel-modal" class="md:w-[500px]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">{{ $isEditing ? 'Modifier le Personnel' : 'Ajouter un Membre du Personnel' }}</flux:heading>
                        <flux:subheading>Renseignez les informations de l'employé.</flux:subheading>
                    </div>

                    <form wire:submit="save" class="space-y-4">
                        <flux:input wire:model="nom" label="Nom Complet" required />
                        <flux:input wire:model="email" type="email" label="Adresse Email" required />
                        <flux:input wire:model="matricule" label="Matricule (ex: PROF-001)" required />
                        <flux:input wire:model="fonction" label="Fonction (ex: Professeur de Mathématiques)" required />
                        
                        <div class="grid grid-cols-2 gap-4">
                            <flux:select wire:model="type_personnel" label="Type de Contrat">
                                <option value="Titulaire">Titulaire</option>
                                <option value="Vacataire">Vacataire</option>
                                <option value="Stagiaire">Stagiaire</option>
                            </flux:select>
                            
                            <flux:select wire:model="role" label="Rôle Système">
                                <option value="professeur">Professeur</option>
                                <option value="admin">Administrateur</option>
                                <option value="surveillant">Surveillant</option>
                                <option value="comptable">Comptable</option>
                            </flux:select>
                        </div>
                        
                        <div class="flex mt-6 space-x-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">Annuler</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" variant="primary">{{ $isEditing ? 'Enregistrer' : 'Ajouter' }}</flux:button>
                        </div>
                    </form>
                </div>
            </flux:modal>

            <!-- Secondary Panel: Pointage -->
            <flux:card class="lg:col-span-4 p-0 overflow-hidden flex flex-col shadow-sm h-full">
                <div class="p-6 border-b border-outline-variant bg-surface-container-low">
                    <div class="flex items-center gap-2 mb-1">
                        <flux:icon.clipboard-document-check class="size-5 text-secondary" />
                        <h3 class="font-title-md text-lg text-primary font-semibold">Pointage du mois</h3>
                    </div>
                    <p class="font-body-sm text-secondary">Vacations en attente de paiement</p>
                </div>
                
                <div class="p-4 flex-1">
                    <ul class="space-y-3 font-body-sm">
                        <!-- Exemple de vacation -->
                        <li class="flex items-center justify-between p-3 rounded-xl border border-outline hover:bg-surface-container-low/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-xs text-primary">
                                    MS
                                </div>
                                <div>
                                    <p class="font-medium text-primary">Mme. Sidibé</p>
                                    <p class="text-secondary text-xs">24h accumulées</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-primary">60 000</p>
                                <p class="text-secondary text-[10px] uppercase tracking-wider">FCFA</p>
                            </div>
                        </li>
                        <li class="flex items-center justify-between p-3 rounded-xl border border-outline hover:bg-surface-container-low/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-xs text-primary">
                                    MO
                                </div>
                                <div>
                                    <p class="font-medium text-primary">M. Ouedraogo</p>
                                    <p class="text-secondary text-xs">12h accumulées</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-primary">30 000</p>
                                <p class="text-secondary text-[10px] uppercase tracking-wider">FCFA</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="p-4 border-t border-outline-variant bg-surface">
                    <flux:button class="w-full" variant="subtle">Voir tout le pointage</flux:button>
                </div>
            </flux:card>

        </div>
    </div>
</div>
