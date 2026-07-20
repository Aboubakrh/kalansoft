<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Professeurs</h2>
                <p class="font-body-lg text-secondary mt-1">Liste des enseignants de l'établissement</p>
            </div>
            <flux:button variant="primary" icon="plus" href="{{ route('admin.professeurs.create') }}">Nouveau Professeur</flux:button>
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher par nom, email ou matricule..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Matricule</flux:table.column>
                    <flux:table.column>Nom Complet</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Statut</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($professeurs as $prof)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-primary">{{ $prof->matricule }}</flux:table.cell>
                            <flux:table.cell>{{ $prof->user->name ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>{{ $prof->user->email ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" color="{{ $prof->statut === 'Actif' ? 'success' : 'zinc' }}">{{ $prof->statut ?? 'Inconnu' }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        <flux:menu.item icon="eye" href="{{ route('admin.professeurs.show', $prof->id) }}">Voir détails & Cours</flux:menu.item>
                                        <flux:menu.item icon="pencil" href="{{ route('admin.professeurs.edit', $prof->id) }}">Modifier</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $prof->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ce professeur et son accès système ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucun professeur trouvé.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $professeurs->links() }}
            </div>
        </flux:card>
    </div>
</div>
