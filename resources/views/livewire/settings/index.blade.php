<div>
    <div class="flex-1 flex overflow-hidden">
        <!-- SUB-MENU DES PARAMÈTRES (Sidebar secondaire) -->
        <aside class="w-64 bg-surface-container-low border-r border-outline-variant p-6 flex-shrink-0 overflow-y-auto hidden lg:block">
            <h2 class="font-title-md text-lg text-primary font-semibold mb-6">Réglages</h2>
            <ul class="space-y-2">
                <li>
                    <a class="block px-3 py-3 rounded-lg bg-surface-container-lowest text-primary transition-all text-sm font-medium" href="#generales">
                        Informations Générales
                    </a>
                </li>
            </ul>
        </aside>

        <!-- CONTENU DES PARAMÈTRES -->
        <div class="flex-1 overflow-y-auto p-4 md:p-10 flex flex-col gap-12 bg-surface">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-headline-lg text-[32px] text-primary font-semibold">Configuration de l'établissement</h2>
                    <p class="text-secondary mt-1">Gérez l'identité et les paramètres structurels de votre établissement.</p>
                </div>
                <flux:button variant="primary" wire:click="saveParametres">
                    Enregistrer les modifications
                </flux:button>
            </div>

            @if(session('status'))
                <flux:badge color="success" class="w-fit">{{ session('status') }}</flux:badge>
            @endif

            <!-- SECTION 1 : Identité de l'école -->
            <section id="generales" class="bg-surface-container-lowest rounded-2xl border border-outline-variant p-8 relative transition-shadow hover:shadow-sm">
                <h3 class="font-title-md text-xl text-primary font-semibold mb-6">Identité de l'école</h3>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <div class="col-span-1 md:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <flux:input wire:model="nom_ecole" label="Nom de l'établissement" />
                        <flux:input wire:model="directeur" label="Directeur" />
                        <flux:input wire:model="telephone" label="Téléphone" type="tel" />
                        <flux:input wire:model="email" label="Email officiel" type="email" />
                    </div>
                    <div class="col-span-1 md:col-span-4 flex flex-col gap-2">
                        <label class="text-sm font-medium text-secondary">Logo de l'établissement</label>
                        <div class="flex-1 border-2 border-dashed border-outline-variant rounded-xl p-6 flex flex-col items-center justify-center bg-surface hover:bg-surface-container-low transition-colors cursor-pointer group">
                            <flux:icon.arrow-up-tray class="size-10 text-secondary group-hover:text-primary transition-colors mb-4" />
                            <p class="text-sm font-medium text-primary text-center">Cliquez pour uploader</p>
                            <p class="text-xs text-secondary text-center mt-1">PNG, JPG (Max 2MB)</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
