<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Ajouter un Professeur</h2>
                <p class="font-body-lg text-secondary mt-1">Créez le profil et les accès du nouvel enseignant.</p>
            </div>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.professeurs.index') }}">Retour à la liste</flux:button>
        </header>

        <flux:card class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <flux:heading size="md" class="mb-4">Informations du compte</flux:heading>
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="prenom" label="Prénom" required />
                        <flux:input wire:model="nom" label="Nom" required />
                    </div>
                    <div class="mt-4">
                        <flux:input wire:model="email" type="email" label="Adresse Email" required />
                    </div>
                    <div class="mt-4 flex items-end gap-2">
                        <div class="flex-1">
                            <flux:input wire:model="password" type="text" label="Mot de passe" required />
                        </div>
                        <flux:button variant="subtle" wire:click="generatePassword">Générer</flux:button>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div>
                    <flux:heading size="md" class="mb-4">Profil Enseignant</flux:heading>
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="matricule" label="Matricule" required />
                        <flux:select wire:model="statut" label="Statut" required>
                            <option value="Actif">Actif</option>
                            <option value="Inactif">Inactif</option>
                            <option value="Congé">En Congé</option>
                        </flux:select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <flux:input wire:model="date_embauche" type="date" label="Date d'embauche (Optionnel)" />
                    </div>
                </div>
                
                <div class="flex mt-6 space-x-2">
                    <flux:spacer />
                    <flux:button variant="ghost" href="{{ route('admin.professeurs.index') }}">Annuler</flux:button>
                    <flux:button type="submit" variant="primary">Créer le Professeur</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>
