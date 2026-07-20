<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Gestion des Élèves</h2>
                <p class="font-body-lg text-secondary mt-1">Liste des élèves inscrits dans l'établissement</p>
            </div>
            <flux:button variant="primary" icon="plus" href="{{ route('admin.eleves.create') }}">Nouvel Élève</flux:button>
        </header>

        <flux:card class="p-6">
            <div class="mb-6 flex justify-between items-center">
                <div class="w-1/3">
                    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher par nom, prénom ou matricule..." />
                </div>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Photo & Nom</flux:table.column>
                    <flux:table.column>Matricule</flux:table.column>
                    <flux:table.column>Date de Naissance</flux:table.column>
                    <flux:table.column>Sexe</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($eleves as $eleve)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    @if($eleve->user->photo)
                                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . $eleve->user->photo) }}" alt="{{ $eleve->user->name }}"/>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                            {{ $eleve->user->initials() }}
                                        </div>
                                    @endif
                                    <span class="font-body-lg text-primary font-medium">{{ $eleve->user->name }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $eleve->matricule }}</flux:table.cell>
                            <flux:table.cell>{{ \Carbon\Carbon::parse($eleve->date_naissance)->translatedFormat('d M Y') }}</flux:table.cell>
                            <flux:table.cell>{{ $eleve->sexe == 'M' ? 'Masculin' : 'Féminin' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        <flux:menu.item icon="eye">Voir profil</flux:menu.item>
                                        <flux:menu.item icon="pencil">Modifier</flux:menu.item>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="trash" variant="danger">Supprimer</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucun élève trouvé.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $eleves->links() }}
            </div>
        </flux:card>
    </div>
</div>
