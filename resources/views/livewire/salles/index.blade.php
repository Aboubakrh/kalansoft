<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Salles</h2>
                <p class="font-body-lg text-secondary mt-1">Liste des salles de classe de l'établissement</p>
            </div>
            
                <flux:button variant="primary" icon="plus" href="{{ route('admin.salles.create') }}">Nouvelle Salle</flux:button>
            
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher une salle..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nom de la Salle</flux:table.column>
                    <flux:table.column>Capacité</flux:table.column>
                    <flux:table.column>Bâtiment</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($salles as $salle)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-primary">{{ $salle->nom }}</flux:table.cell>
                            <flux:table.cell>{{ $salle->capacite }} places</flux:table.cell>
                            <flux:table.cell>{{ $salle->batiment ?? 'Non spécifié' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        
                                            <flux:menu.item icon="pencil" href="{{ route('admin.salles.edit', $salle->id) }}">Modifier</flux:menu.item>
                                        
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $salle->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette salle ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center text-secondary py-4">Aucune salle trouvée.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $salles->links() }}
            </div>
        </flux:card>

        
    </div>
</div>
