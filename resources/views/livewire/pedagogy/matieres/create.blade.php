<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Ajouter une Matière</h2>
                <p class="font-body-lg text-secondary mt-1">Définissez les caractéristiques de la matière.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.matieres.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="nom" label="Nom (ex: Mathématiques)" required />
                    <flux:input wire:model="code" label="Code (ex: MATH)" required />
                </div>
                
                <div class="grid grid-cols-2 gap-4 items-center">
                    <flux:input wire:model="couleur" type="color" label="Couleur d'affichage" />
                    <div class="mt-8">
                        <flux:checkbox wire:model="est_optionnelle" label="Matière Optionnelle" />
                    </div>
                </div>
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.matieres.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Ajouter la Matière</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
