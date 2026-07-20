<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <div class="flex items-center gap-2 text-secondary mb-1">
                    <a href="{{ route('admin.evaluations.index') }}" class="hover:text-primary hover:underline">Évaluations</a>
                    <flux:icon.chevron-right class="size-3" />
                    <span>Saisie des notes</span>
                </div>
                <h2 class="font-headline-lg text-[24px] text-primary">Notes : {{ $evaluation->cours->matiere->nom }} ({{ $evaluation->cours->classe->nom }})</h2>
                <p class="font-body-lg text-secondary mt-1">
                    {{ $evaluation->type_evaluation }} du {{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }} 
                    | Période : {{ $evaluation->periode }}
                    | Barème : /{{ (int)$evaluation->bareme }}
                    | Coef : {{ (float)$evaluation->coefficient }}
                </p>
            </div>
            <div class="flex gap-2">
                <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.evaluations.index') }}">Retour</flux:button>
                <flux:button variant="primary" icon="document-check" wire:click="save">Enregistrer les notes</flux:button>
            </div>
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-surface-container-low border-b border-outline">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-secondary w-16">N°</th>
                            <th class="px-6 py-4 font-semibold text-secondary w-1/4">Élève</th>
                            <th class="px-6 py-4 font-semibold text-secondary w-48">Note (sur {{ (int)$evaluation->bareme }})</th>
                            <th class="px-6 py-4 font-semibold text-secondary">Appréciation / Observation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline">
                        @forelse($inscriptions as $index => $insc)
                            @php
                                $eleveId = $insc->eleve_id;
                            @endphp
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-6 py-4 text-secondary">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($insc->eleve->user->photo)
                                            <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . $insc->eleve->user->photo) }}" alt="Photo"/>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                                                {{ $insc->eleve->user->initials() }}
                                            </div>
                                        @endif
                                        <div class="font-medium text-primary">{{ $insc->eleve->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-24">
                                        <flux:input 
                                            wire:model.defer="notes.{{ $eleveId }}.valeur" 
                                            type="number" 
                                            step="0.25" 
                                            min="0" 
                                            max="{{ $evaluation->bareme }}"
                                            placeholder="--/{{ (int)$evaluation->bareme }}"
                                            class="text-right font-bold"
                                        />
                                    </div>
                                    @error('notes.'.$eleveId.'.valeur')
                                        <span class="text-xs text-danger mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-6 py-4">
                                    <flux:input 
                                        wire:model.defer="notes.{{ $eleveId }}.observation" 
                                        placeholder="Commentaire..."
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-secondary">
                                    Aucun élève inscrit dans cette classe.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(count($inscriptions) > 0)
            <div class="p-4 bg-surface-container-low border-t border-outline flex justify-between items-center">
                <div class="text-sm text-secondary">
                    N'oubliez pas d'enregistrer après la saisie !
                </div>
                <flux:button variant="primary" icon="document-check" wire:click="save">Enregistrer les notes</flux:button>
            </div>
            @endif
        </flux:card>
    </div>
</div>
