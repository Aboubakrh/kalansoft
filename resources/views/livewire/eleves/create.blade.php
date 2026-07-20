<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Nouvel Élève</h2>
                <p class="font-body-lg text-secondary mt-1">Inscription d'un élève et rattachement de son tuteur</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.eleves.index') }}">Retour à la liste</flux:button>
        </header>

        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations Élève -->
                <flux:card class="p-6">
                    <flux:heading size="lg" class="mb-6">Informations de l'Élève</flux:heading>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="nom" label="Nom" required />
                            <flux:input wire:model="prenom" label="Prénom" required />
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="matricule" label="Matricule" required />
                            <flux:select wire:model="sexe" label="Sexe" required>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </flux:select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="date_naissance" type="date" label="Date de naissance" required />
                            <flux:input wire:model="lieu_naissance" label="Lieu de naissance" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="telephone" label="Téléphone (Élève)" />
                            <flux:input wire:model="email" type="email" label="Email (Élève)" />
                        </div>
                    </div>
                </flux:card>

                <!-- Informations Tuteur & Inscription -->
                <div class="space-y-8">
                    <flux:card class="p-6">
                        <flux:heading size="lg" class="mb-6">Informations du Tuteur</flux:heading>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <flux:input wire:model="parent_nom" label="Nom du tuteur" required />
                                <flux:input wire:model="parent_prenom" label="Prénom du tuteur" required />
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <flux:input wire:model="parent_telephone" label="Téléphone principal" required />
                                <flux:input wire:model="parent_email" type="email" label="Email" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <flux:input wire:model="parent_profession" label="Profession" />
                                <flux:input wire:model="parent_adresse" label="Adresse physique" />
                            </div>
                        </div>
                    </flux:card>

                    <flux:card class="p-6 bg-surface-container-low border-primary border-2">
                        <flux:heading size="lg" class="mb-4 text-primary">Détails de l'Inscription</flux:heading>
                        
                        <flux:select wire:model="classe_id" label="Classe" required>
                            <option value="">Sélectionnez une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </flux:select>
                        
                        <div class="mt-6 flex justify-end">
                            <flux:button type="submit" variant="primary" icon="check" class="w-full sm:w-auto">
                                Enregistrer et Inscrire
                            </flux:button>
                        </div>
                    </flux:card>
                </div>
            </div>
        </form>
    </div>
</div>
