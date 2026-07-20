<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Matières</h2>
                <p class="font-body-lg text-secondary mt-1">Catalogue des matières enseignées</p>
            </div>
            
                <flux:button variant="primary" icon="plus" href="{{ route('admin.matieres.create') }}">Nouvelle Matière</flux:button>
            
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher une matière..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Nom de la Matière</flux:table.column>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column>Couleur</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($matieres as $matiere)
                        <flux:table.row>
                            <flux:table.cell class="font-bold">{{ $matiere->code }}</flux:table.cell>
                            <flux:table.cell class="font-medium text-primary">{{ $matiere->nom }}</flux:table.cell>
                            <flux:table.cell>
                                @if($matiere->est_optionnelle)
                                    <flux:badge size="sm" color="warning">Optionnelle</flux:badge>
                                @else
                                    <flux:badge size="sm" color="success">Obligatoire</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="w-6 h-6 rounded-full border border-outline" style="background-color: {{ $matiere->couleur ?? '#CBD5E1' }}"></div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        
                                            <flux:menu.item icon="pencil" href="{{ route('admin.matieres.edit', $matiere->id) }}">Modifier</flux:menu.item>
                                        
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $matiere->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette matière ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucune matière trouvée.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $matieres->links() }}
            </div>
        </flux:card>

        
    </div>
</div>
