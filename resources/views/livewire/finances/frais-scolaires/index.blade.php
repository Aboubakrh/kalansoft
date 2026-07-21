<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Frais de Scolarité</flux:heading>
            <flux:subheading>Gérez les montants des frais de scolarité par classe et par année scolaire.</flux:subheading>
        </div>
        <flux:modal.trigger name="frais-modal">
            <flux:button wire:click="create" variant="primary" icon="plus">Définir des frais</flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Stats & Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
        <div class="w-full sm:w-1/3">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher (classe, année)..." />
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
                <flux:table.column>Année Scolaire</flux:table.column>
                <flux:table.column>Classe</flux:table.column>
                <flux:table.column>Montant</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($frais as $item)
                    <flux:table.row>
                        <flux:table.cell>
                            @if($item->anneeScolaire->est_active)
                                <flux:badge size="sm" color="success">{{ $item->anneeScolaire->libelle }}</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">{{ $item->anneeScolaire->libelle }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell class="font-medium text-primary">{{ $item->classe->nom ?? 'Inconnue' }}</flux:table.cell>
                        <flux:table.cell class="font-bold">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:modal.trigger name="frais-modal">
                                        <flux:menu.item wire:click="edit({{ $item->id }})" icon="pencil">Modifier</flux:menu.item>
                                    </flux:modal.trigger>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="delete({{ $item->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer ces frais ?" icon="trash" variant="danger">Supprimer</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-secondary py-8">Aucun frais défini.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
        <div class="mt-4">
            {{ $frais->links() }}
        </div>
    </flux:card>

    <!-- Modal Form -->
    <flux:modal name="frais-modal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $fraisId ? 'Modifier les frais' : 'Définir des frais' }}</flux:heading>
                <flux:subheading>Précisez la classe, l'année et le montant.</flux:subheading>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:select wire:model="annee_scolaire_id" label="Année Scolaire" required>
                    <flux:select.option value="" disabled selected>Choisir une année</flux:select.option>
                    @foreach($annees as $annee)
                        <flux:select.option value="{{ $annee->id }}">{{ $annee->libelle }} {{ $annee->est_active ? '(Active)' : '' }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="classe_id" label="Classe" required>
                    <flux:select.option value="" disabled selected>Choisir une classe</flux:select.option>
                    @foreach($classes as $classe)
                        <flux:select.option value="{{ $classe->id }}">{{ $classe->nom }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="montant" type="number" step="1" min="0" label="Montant Total (FCFA)" placeholder="Ex: 150000" required />

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
