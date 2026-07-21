<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- En-tête -->
            <header class="mb-6">
                <h2 class="font-headline-lg text-[24px] text-primary">Faire l'appel</h2>
                <p class="font-body-lg text-secondary mt-1">Gérez les présences de vos élèves pour chaque cours.</p>
            </header>

            @if (session()->has('success'))
                <flux:card class="p-4 bg-green-50 border-green-200">
                    <div class="flex items-center gap-3 text-green-800">
                        <flux:icon.check-circle class="size-6" />
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </flux:card>
            @endif

            @if($this->mesCours->isEmpty())
                <flux:card class="p-12 text-center">
                    <div class="bg-surface rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                        <flux:icon.book-open class="size-6 text-secondary" />
                    </div>
                    <h3 class="text-lg font-semibold text-primary">Aucun cours assigné</h3>
                    <p class="text-secondary mt-2">Vous n'avez actuellement aucun cours assigné pour cette année scolaire.</p>
                </flux:card>
            @else
                <form wire:submit="save">
                    <!-- Filtres -->
                    <flux:card class="p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <flux:select wire:model.live="cours_id" label="Sélectionner le cours">
                                    @foreach($this->mesCours as $cours)
                                        <flux:select.option value="{{ $cours->id }}">
                                            {{ $cours->classe->nom ?? 'N/A' }} - {{ $cours->matiere->nom ?? 'N/A' }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div>
                                <flux:input type="date" wire:model.live="date" label="Date de l'appel" />
                            </div>
                        </div>
                    </flux:card>

                    <!-- Grille d'appel -->
                    <flux:card class="p-0 overflow-hidden">
                        @if($this->eleves->isNotEmpty())
                            <div class="p-4 bg-surface border-b border-surface-variant flex justify-between items-center">
                                <h3 class="font-semibold text-primary">Liste des élèves ({{ $this->eleves->count() }})</h3>
                                <div class="flex items-center gap-2 text-sm text-secondary">
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Présent</span>
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Absent</span>
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Retard</span>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-surface-container-lowest border-b border-surface-variant">
                                        <tr>
                                            <th class="px-6 py-4 font-label-caps text-xs text-secondary uppercase tracking-wider w-1/4">Élève</th>
                                            <th class="px-6 py-4 font-label-caps text-xs text-secondary uppercase tracking-wider w-1/3">Statut</th>
                                            <th class="px-6 py-4 font-label-caps text-xs text-secondary uppercase tracking-wider">Observation (optionnel)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-surface-variant bg-white">
                                        @foreach($this->eleves as $eleve)
                                            <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="font-title-md text-primary">{{ $eleve->user?->prenom }} {{ $eleve->user?->nom }}</span>
                                                        <span class="font-body-sm text-secondary">{{ $eleve->matricule }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-4">
                                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                                            <input type="radio" wire:model="attendances.{{ $eleve->id }}.statut" value="Présent" class="text-green-600 focus:ring-green-600 w-4 h-4 border-gray-300">
                                                            <span class="text-sm font-medium text-gray-700">P</span>
                                                        </label>
                                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                                            <input type="radio" wire:model="attendances.{{ $eleve->id }}.statut" value="Absent" class="text-red-600 focus:ring-red-600 w-4 h-4 border-gray-300">
                                                            <span class="text-sm font-medium text-gray-700">A</span>
                                                        </label>
                                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                                            <input type="radio" wire:model="attendances.{{ $eleve->id }}.statut" value="Retard" class="text-orange-500 focus:ring-orange-500 w-4 h-4 border-gray-300">
                                                            <span class="text-sm font-medium text-gray-700">R</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <flux:input 
                                                        wire:model="attendances.{{ $eleve->id }}.observation" 
                                                        placeholder="Motif, justification..." 
                                                        class="w-full"
                                                        {{ ($attendances[$eleve->id]['statut'] ?? 'Présent') === 'Présent' ? 'disabled' : '' }}
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-6 bg-surface-container-lowest border-t border-surface-variant flex justify-end">
                                <flux:button type="submit" variant="primary" icon="check">
                                    Enregistrer l'appel
                                </flux:button>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="bg-surface rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                                    <flux:icon.users class="size-6 text-secondary" />
                                </div>
                                <h3 class="text-lg font-semibold text-primary">Aucun élève trouvé</h3>
                                <p class="text-secondary mt-2">Aucun élève n'est inscrit dans cette classe pour l'année en cours.</p>
                            </div>
                        @endif
                    </flux:card>
                </form>
            @endif
        </div>
    </div>
</div>
