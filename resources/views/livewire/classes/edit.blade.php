<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Modifier la Classe</h2>
                <p class="font-body-lg text-secondary mt-1">Modifiez les informations de la classe {{ $nom }}.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.classes.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="nom" label="Nom (ex: Terminale TSE)" required />
                    <flux:input wire:model="niveau" label="Niveau (ex: Lycée)" required />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="capacite_maximale" type="number" label="Capacité Maximale" required />
                    <flux:select wire:model="salle_id" label="Salle Assignée">
                        <option value="">-- Sans Salle --</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}">{{ $salle->nom }} ({{ $salle->capacite }} places)</option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:select wire:model="serie_id" label="Série (Optionnel)">
                    <option value="">-- Sans Série --</option>
                    @foreach($series as $serie)
                        <option value="{{ $serie->id }}">{{ $serie->nom }} ({{ $serie->code }})</option>
                    @endforeach
                </flux:select>
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.classes.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Enregistrer les modifications</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
