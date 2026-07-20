<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Cours</h2>
                <p class="font-body-lg text-secondary mt-1">Attribution des matières et professeurs aux classes</p>
            </div>
            
                <flux:button variant="primary" icon="plus" href="{{ route('admin.cours.create') }}">Nouveau Cours</flux:button>
            
        </header>

        @if(session('status'))
            <div class="mb-4">
                <flux:badge color="success">{{ session('status') }}</flux:badge>
            </div>
        @endif

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher par classe ou matière..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Classe</flux:table.column>
                    <flux:table.column>Matière</flux:table.column>
                    <flux:table.column>Professeur (Optionnel)</flux:table.column>
                    <flux:table.column>Taux Horaire</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($cours as $c)
                        <flux:table.row>
                            <flux:table.cell class="font-bold text-primary">{{ $c->classe->nom }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $c->matiere->couleur ?? '#CBD5E1' }}"></div>
                                    <span>{{ $c->matiere->nom }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($c->enseignant)
                                    <span class="font-medium">{{ $c->enseignant->user->name }}</span>
                                @else
                                    <span class="text-warning italic">Non assigné</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $c->taux_horaire_vacation > 0 ? number_format($c->taux_horaire_vacation, 0) . ' F/h' : '-' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        
                                            <flux:menu.item icon="pencil" href="{{ route('admin.cours.edit', $c->id) }}">Modifier</flux:menu.item>
                                        
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $c->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ce cours ?">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucun cours trouvé.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $cours->links() }}
            </div>
        </flux:card>

        
    </div>
</div>
