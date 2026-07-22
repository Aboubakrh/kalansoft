<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Mes Classes</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez les classes dans lesquelles vous enseignez.</p>
                </div>
            </header>

            @if($this->classes->isEmpty())
                <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                    <flux:icon.user-group class="size-8 mx-auto text-gray-400 mb-3" />
                    <p class="text-secondary">Aucune classe ne vous a encore été attribuée cette année.</p>
                </flux:card>
            @else
                <!-- Class Selection Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->classes as $classe)
                        @php
                            $isSelected = $this->selectedClasse && $this->selectedClasse->id === $classe->id;
                        @endphp
                        <flux:card wire:click="selectClasse({{ $classe->id }})" 
                            class="p-5 cursor-pointer transition-all border-2 {{ $isSelected ? 'border-primary bg-primary/5 shadow-md' : 'border-surface-variant hover:border-primary/50' }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-bold text-primary">{{ $classe->nom }}</h3>
                                    <span class="text-xs text-secondary">{{ $classe->serie->nom ?? 'Général' }}</span>
                                </div>
                                <flux:badge color="{{ $isSelected ? 'blue' : 'zinc' }}">
                                    {{ $classe->inscriptions->count() }} élève(s)
                                </flux:badge>
                            </div>
                            <div class="text-xs text-secondary flex items-center justify-between border-t border-surface-variant/60 pt-3 mt-3">
                                <span>Salle : {{ $classe->salle->nom ?? 'Non attribuée' }}</span>
                                <span class="font-semibold text-primary">Sélectionner &rarr;</span>
                            </div>
                        </flux:card>
                    @endforeach
                </div>

                @if($this->selectedClasse)
                    <!-- Selected Class Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                        <!-- Student List (2 cols) -->
                        <div class="lg:col-span-2 space-y-4">
                            <flux:card class="p-6">
                                <div class="flex justify-between items-center mb-4 pb-3 border-b border-surface-variant">
                                    <div>
                                        <h3 class="text-lg font-bold text-primary">Liste des élèves - {{ $this->selectedClasse->nom }}</h3>
                                        <p class="text-xs text-secondary">Effectif total : {{ $this->eleves->count() }} élèves</p>
                                    </div>
                                </div>

                                @if($this->eleves->isEmpty())
                                    <p class="text-center text-secondary py-8">Aucun élève inscrit dans cette classe.</p>
                                @else
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm">
                                            <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary text-xs uppercase">
                                                <tr>
                                                    <th class="px-4 py-3">Matricule</th>
                                                    <th class="px-4 py-3">Nom & Prénom</th>
                                                    <th class="px-4 py-3">Genre</th>
                                                    <th class="px-4 py-3">Date de naissance</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-surface-variant">
                                                @foreach($this->eleves as $eleve)
                                                    <tr class="hover:bg-surface-container-lowest/50">
                                                        <td class="px-4 py-3 font-mono text-xs font-semibold text-primary">
                                                            {{ $eleve->matricule }}
                                                        </td>
                                                        <td class="px-4 py-3 font-medium text-primary">
                                                            {{ $eleve->user->nom ?? '' }} {{ $eleve->user->prenom ?? '' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-secondary">
                                                            {{ $eleve->sexe ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-4 py-3 text-secondary text-xs">
                                                            {{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </flux:card>
                        </div>

                        <!-- Subjects taught in this class (1 col) -->
                        <div class="space-y-4">
                            <flux:card class="p-6">
                                <h3 class="text-lg font-bold text-primary mb-4 pb-3 border-b border-surface-variant">Mes Cours dans cette classe</h3>
                                
                                @if($this->mesCours->isEmpty())
                                    <p class="text-secondary text-sm">Aucun cours trouvé.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($this->mesCours as $cours)
                                            <div class="p-3 bg-surface-container-low rounded-lg border border-surface-variant">
                                                <h4 class="font-bold text-primary text-sm">{{ $cours->matiere->nom ?? 'N/A' }}</h4>
                                                <p class="text-xs text-secondary mt-1">Code cours : {{ $cours->id }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </flux:card>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
