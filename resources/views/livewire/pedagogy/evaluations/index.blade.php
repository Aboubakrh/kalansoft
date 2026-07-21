<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Évaluations</h2>
                <p class="font-body-lg text-secondary mt-1">Créez des devoirs ou compos pour saisir les notes</p>
            </div>
            
                <flux:button variant="primary" icon="plus" href="{{ route('admin.evaluations.create') }}">Nouvelle Évaluation</flux:button>
            
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher classe, matière..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Classe & Matière</flux:table.column>
                    <flux:table.column>Type & Période</flux:table.column>
                    <flux:table.column>Barème (Coef)</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($evaluations as $eval)
                        <flux:table.row>
                            <flux:table.cell class="text-secondary">{{ \Carbon\Carbon::parse($eval->date_evaluation)->format('d/m/Y') }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="font-bold text-primary">{{ $eval->cours->classe->nom }}</div>
                                <div class="text-sm flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full" style="background-color: {{ $eval->cours->matiere->couleur ?? '#ccc' }}"></div>
                                    {{ $eval->cours->matiere->nom }}
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div><flux:badge size="sm" color="zinc">{{ $eval->type_evaluation }}</flux:badge></div>
                                <div class="text-xs text-secondary mt-1">{{ $eval->periode }}</div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="font-medium">/{{ (int)$eval->bareme }}</span> 
                                <span class="text-secondary text-sm">(x{{ (int)$eval->coefficient }})</span>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:button variant="primary" size="sm" icon="pencil-square" href="{{ route('admin.notes.saisie', $eval->id) }}">
                                    Saisir Notes
                                </flux:button>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" class="ml-2" />
                                    <flux:menu>
                                        
                                            <flux:menu.item icon="pencil" href="{{ route('admin.evaluations.edit', $eval->id) }}">Modifier l'entête</flux:menu.item>
                                        
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $eval->id }})" wire:confirm="Supprimer l'évaluation et toutes ses notes ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucune évaluation trouvée.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $evaluations->links() }}
            </div>
        </flux:card>

        
    </div>
</div>
