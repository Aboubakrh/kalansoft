<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Classes</h2>
                <p class="font-body-lg text-secondary mt-1">Liste des classes et affectation des salles</p>
            </div>
            <flux:button variant="primary" icon="plus" href="{{ route('admin.classes.create') }}">Nouvelle Classe</flux:button>
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher une classe..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nom de la Classe</flux:table.column>
                    <flux:table.column>Niveau</flux:table.column>
                    <flux:table.column>Salle Assignée</flux:table.column>
                    <flux:table.column>Capacité</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($classes as $classe)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-primary">{{ $classe->nom }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" color="zinc">{{ $classe->niveau }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($classe->salle)
                                    <span class="text-secondary">{{ $classe->salle->nom }} ({{ $classe->salle->batiment }})</span>
                                @else
                                    <span class="text-danger italic">Non assignée</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $classe->capacite_maximale }} places</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        <flux:menu.item icon="pencil" href="{{ route('admin.classes.edit', $classe->id) }}">Modifier</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $classe->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette classe ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucune classe trouvée.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $classes->links() }}
            </div>
        </flux:card>

        
    </div>
</div>
