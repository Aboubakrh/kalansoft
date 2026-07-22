<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Pointage du Personnel</h2>
                    <p class="font-body-lg text-secondary mt-1">Enregistrez les heures d'arrivée et de départ du personnel.</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <label class="text-sm font-semibold text-primary">Date :</label>
                    <input type="date" 
                        wire:model.live="date" 
                        class="p-2 border border-surface-variant rounded-lg bg-white text-primary text-sm font-medium focus:ring-2 focus:ring-primary focus:outline-none"
                    />
                </div>
            </header>

            @if(session('status'))
                <flux:badge color="green" class="p-3 mb-4 w-full">
                    {{ session('status') }}
                </flux:badge>
            @endif

            @if($this->personnels->isEmpty())
                <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                    <flux:icon.users class="size-8 mx-auto text-gray-400 mb-3" />
                    <p class="text-secondary">Aucun membre du personnel enregistré dans le système.</p>
                </flux:card>
            @else
                <flux:card class="p-0 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3">Matricule</th>
                                    <th class="px-6 py-3">Nom & Prénom</th>
                                    <th class="px-6 py-3">Fonction</th>
                                    <th class="px-6 py-3 text-center">Heure Arrivée</th>
                                    <th class="px-6 py-3 text-center">Heure Départ</th>
                                    <th class="px-6 py-3 text-right">Actions rapides</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-variant bg-white">
                                @foreach($this->personnels as $p)
                                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs font-semibold text-primary">
                                            {{ $p->matricule }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-primary">
                                            {{ $p->user->nom ?? '' }} {{ $p->user->prenom ?? '' }}
                                            <span class="block text-xs font-normal text-secondary">{{ $p->type_personnel }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-secondary">
                                            {{ $p->fonction }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="time" 
                                                wire:model.blur="pointages.{{ $p->id }}.heure_arrivee"
                                                wire:change="saveRow({{ $p->id }})"
                                                class="p-2 border border-surface-variant rounded-lg text-center font-bold text-primary bg-surface-container-lowest w-32 mx-auto focus:ring-2 focus:ring-primary focus:outline-none"
                                            />
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="time" 
                                                wire:model.blur="pointages.{{ $p->id }}.heure_depart"
                                                wire:change="saveRow({{ $p->id }})"
                                                class="p-2 border border-surface-variant rounded-lg text-center font-bold text-primary bg-surface-container-lowest w-32 mx-auto focus:ring-2 focus:ring-primary focus:outline-none"
                                            />
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <flux:button size="xs" variant="subtle" wire:click="setNowArrivee({{ $p->id }})" title="Pointer arrivée maintenant">
                                                    Arrivée
                                                </flux:button>
                                                <flux:button size="xs" variant="subtle" wire:click="setNowDepart({{ $p->id }})" title="Pointer départ maintenant">
                                                    Départ
                                                </flux:button>
                                                <flux:button size="xs" variant="ghost" color="red" wire:click="clearRow({{ $p->id }})" title="Réinitialiser">
                                                    &times;
                                                </flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>
</div>
