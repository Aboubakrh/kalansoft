<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Modifier l'Évaluation</h2>
                <p class="font-body-lg text-secondary mt-1">Mettez à jour les paramètres de cette évaluation.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.evaluations.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-4">
                <flux:select wire:model="cours_id" label="Cours (Classe - Matière)" required>
                    <option value="">Sélectionnez le cours</option>
                    @foreach($cours as $c)
                        <option value="{{ $c->id }}">{{ $c->classe->nom }} - {{ $c->matiere->nom }}</option>
                    @endforeach
                </flux:select>

                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="type_evaluation" label="Type d'Évaluation" required>
                        <option value="Devoir">Devoir de Classe</option>
                        <option value="Interrogation">Interrogation</option>
                        <option value="Composition">Composition</option>
                        <option value="Examen">Examen Blanc</option>
                    </flux:select>
                    <flux:select wire:model="periode" label="Période" required>
                        <option value="Trimestre 1">Trimestre 1</option>
                        <option value="Trimestre 2">Trimestre 2</option>
                        <option value="Trimestre 3">Trimestre 3</option>
                        <option value="Semestre 1">Semestre 1</option>
                        <option value="Semestre 2">Semestre 2</option>
                    </flux:select>
                </div>
                
                <div class="grid grid-cols-3 gap-4">
                    <flux:input wire:model="date_evaluation" type="date" label="Date" required />
                    <flux:input wire:model="bareme" type="number" label="Barème (sur)" required />
                    <flux:input wire:model="coefficient" type="number" step="0.5" label="Coefficient" required />
                </div>
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.evaluations.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Enregistrer les modifications</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
