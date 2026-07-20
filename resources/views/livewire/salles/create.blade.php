<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Ajouter une Salle</h2>
                <p class="font-body-lg text-secondary mt-1">Renseignez les informations de la nouvelle salle.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.salles.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="nom" label="Nom (ex: Salle 101)" required />
                <flux:input wire:model="capacite" type="number" label="Capacité (en nombre de places)" required />
                <flux:input wire:model="batiment" label="Bâtiment (Optionnel)" />
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.salles.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Ajouter la Salle</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
