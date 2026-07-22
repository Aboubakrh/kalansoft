<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Matrice des Coefficients</h2>
                    <p class="font-body-lg text-secondary mt-1">Définissez les coefficients de chaque matière par série.</p>
                </div>
            </header>

            @if(session('status'))
                <flux:badge color="green" class="p-3 mb-4 w-full">
                    {{ session('status') }}
                </flux:badge>
            @endif

            @if($this->matieres->isEmpty() || $this->series->isEmpty())
                <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                    <flux:icon.rectangle-stack class="size-8 mx-auto text-gray-400 mb-3" />
                    <p class="text-secondary">Veuillez d'abord configurer des matières et des séries dans le système.</p>
                </flux:card>
            @else
                <flux:card class="p-6 overflow-x-auto">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-surface-variant">
                                    <th class="px-6 py-4 font-bold text-primary sticky left-0 bg-surface-container-low z-10 min-w-[200px]">
                                        Matière / Série
                                    </th>
                                    @foreach($this->series as $serie)
                                        <th class="px-6 py-4 font-bold text-primary text-center min-w-[120px]">
                                            {{ $serie->nom }}
                                            <span class="block text-xs font-normal text-secondary">{{ $serie->code }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-variant bg-white">
                                @foreach($this->matieres as $matiere)
                                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-primary sticky left-0 bg-white z-10 border-r border-surface-variant">
                                            {{ $matiere->nom }}
                                            <span class="block text-xs font-normal text-secondary">{{ $matiere->code }}</span>
                                        </td>
                                        @foreach($this->series as $serie)
                                            @php
                                                $valeur = $coefficients[$matiere->id][$serie->id] ?? '';
                                            @endphp
                                            <td class="px-4 py-3 text-center">
                                                <input type="number" 
                                                    min="0" 
                                                    max="20"
                                                    value="{{ $valeur }}"
                                                    wire:change="updateCoefficient({{ $matiere->id }}, {{ $serie->id }}, $event.target.value)"
                                                    class="w-16 mx-auto text-center font-bold text-primary bg-surface-container-lowest border border-surface-variant rounded-lg p-2 focus:ring-2 focus:ring-primary focus:outline-none"
                                                    placeholder="0"
                                                />
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>
</div>
