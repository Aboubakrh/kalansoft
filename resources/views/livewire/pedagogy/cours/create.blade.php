<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Ajouter un Cours</h2>
                <p class="font-body-lg text-secondary mt-1">Associez une classe, une matière et un professeur.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.cours.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="classe_id" label="Classe" required>
                        <option value="">Sélectionnez une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="matiere_id" label="Matière" required>
                        <option value="">Sélectionnez une matière</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                        @endforeach
                    </flux:select>
                </div>

                @error('matiere_id')
                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                @enderror
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="enseignant_id" label="Professeur (Optionnel)">
                        <option value="">-- Non assigné --</option>
                        @foreach($professeurs as $prof)
                            <option value="{{ $prof->id }}">{{ $prof->user->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:input wire:model="taux_horaire_vacation" type="number" label="Taux Horaire Vacation (F/h)" />
                </div>
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.cours.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Ajouter le Cours</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
