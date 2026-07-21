<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Séries</flux:heading>
            <flux:subheading>Gérez les séries pédagogiques de votre établissement (ex: Sciences, Lettres, etc.)</flux:subheading>
        </div>
        <flux:modal.trigger name="serie-modal">
            <flux:button wire:click="create" variant="primary" icon="plus">Nouvelle Série</flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Stats & Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
        <div class="w-full sm:w-1/3">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher une série..." />
        </div>
    </div>

    <!-- Messages -->
    @if (session()->has('success'))
        <flux:toast variant="success">{{ session('success') }}</flux:toast>
    @endif

    <!-- Table -->
    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Code</flux:table.column>
                <flux:table.column>Nom de la série</flux:table.column>
                <flux:table.column>Cycle</flux:table.column>
                <flux:table.column>Ordre</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($series as $serie)
                    <flux:table.row>
                        <flux:table.cell><flux:badge size="sm" color="zinc">{{ $serie->code }}</flux:badge></flux:table.cell>
                        <flux:table.cell class="font-medium text-primary">{{ $serie->nom }}</flux:table.cell>
                        <flux:table.cell>{{ $serie->cycle ?? '-' }}</flux:table.cell>
                        <flux:table.cell>{{ $serie->ordre ?? '-' }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:modal.trigger name="serie-modal">
                                        <flux:menu.item wire:click="edit({{ $serie->id }})" icon="pencil">Modifier</flux:menu.item>
                                    </flux:modal.trigger>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="delete({{ $serie->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette série ?" icon="trash" variant="danger">Supprimer</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center text-secondary py-8">Aucune série trouvée.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
        <div class="mt-4">
            {{ $series->links() }}
        </div>
    </flux:card>

    <!-- Modal Form -->
    <flux:modal name="serie-modal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $serieId ? 'Modifier la série' : 'Nouvelle Série' }}</flux:heading>
                <flux:subheading>Veuillez remplir les informations de la série.</flux:subheading>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="code" label="Code de la série" placeholder="Ex: S1, L, TSE" required />
                <flux:input wire:model="nom" label="Nom de la série" placeholder="Ex: Sciences Expérimentales" required />
                <flux:input wire:model="cycle" label="Cycle (Optionnel)" placeholder="Ex: Secondaire 2nd cycle" />
                <flux:input wire:model="ordre" type="number" label="Ordre d'affichage (Optionnel)" placeholder="Ex: 1" />

                <div class="flex justify-end gap-2 mt-6">
                    <flux:modal.close>
                        <flux:button variant="ghost">Annuler</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Enregistrer</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
