<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl">Années Scolaires</flux:heading>
            <flux:subheading>Gérez les années scolaires et définissez l'année en cours.</flux:subheading>
        </div>
        <flux:modal.trigger name="annee-modal">
            <flux:button wire:click="create" variant="primary" icon="plus">Nouvelle Année</flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Messages -->
    @if (session()->has('success'))
        <flux:toast variant="success">{{ session('success') }}</flux:toast>
    @endif
    @if (session()->has('error'))
        <flux:toast variant="danger">{{ session('error') }}</flux:toast>
    @endif

    <!-- Table -->
    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Libellé</flux:table.column>
                <flux:table.column>Statut</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($annees as $annee)
                    <flux:table.row>
                        <flux:table.cell class="font-medium text-primary">{{ $annee->libelle }}</flux:table.cell>
                        <flux:table.cell>
                            @if($annee->est_active)
                                <flux:badge size="sm" color="success" icon="check-circle">Année en cours</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">Archivée</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    @if(!$annee->est_active)
                                        <flux:menu.item wire:click="activate({{ $annee->id }})" icon="check">Définir comme active</flux:menu.item>
                                    @endif
                                    <flux:modal.trigger name="annee-modal">
                                        <flux:menu.item wire:click="edit({{ $annee->id }})" icon="pencil">Modifier</flux:menu.item>
                                    </flux:modal.trigger>
                                    <flux:menu.separator />
                                    <flux:menu.item wire:click="delete({{ $annee->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cette année scolaire ?" icon="trash" variant="danger">Supprimer</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center text-secondary py-8">Aucune année scolaire trouvée.</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
        <div class="mt-4">
            {{ $annees->links() }}
        </div>
    </flux:card>

    <!-- Modal Form -->
    <flux:modal name="annee-modal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $anneeId ? 'Modifier l\'année' : 'Nouvelle Année' }}</flux:heading>
                <flux:subheading>Format attendu : 2025-2026</flux:subheading>
            </div>

            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="libelle" label="Libellé" placeholder="Ex: 2026-2027" required />
                
                <flux:checkbox wire:model="est_active" label="Définir comme année en cours" />

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
