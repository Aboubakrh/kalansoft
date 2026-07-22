<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="mb-8">
                <h2 class="font-headline-lg text-[24px] text-primary">Mes Pointages & Assiduité</h2>
                <p class="font-body-lg text-secondary mt-1">Consultez l'historique de vos heures d'arrivée et de départ enregistrées par l'administration.</p>
            </header>

            @if(!$this->personnel)
                <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                    <p class="text-secondary">Aucun profil personnel associé à votre compte.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <flux:card class="p-6 text-center border-blue-100 bg-blue-50/30">
                        <h3 class="text-secondary font-medium">Jours Pointés</h3>
                        <div class="text-3xl font-bold text-primary mt-2">{{ $this->stats['totalJours'] }}</div>
                    </flux:card>
                    <flux:card class="p-6 text-center border-green-100 bg-green-50/30">
                        <h3 class="text-secondary font-medium">Journées Complètes (Arrivée & Départ)</h3>
                        <div class="text-3xl font-bold text-green-600 mt-2">{{ $this->stats['joursComplets'] }}</div>
                    </flux:card>
                </div>

                @if($this->pointages->isEmpty())
                    <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                        <flux:icon.clock class="size-8 mx-auto text-gray-400 mb-3" />
                        <p class="text-secondary">Aucun pointage n'a été enregistré pour le moment.</p>
                    </flux:card>
                @else
                    <flux:card class="p-0 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3 text-center">Heure d'arrivée</th>
                                        <th class="px-6 py-3 text-center">Heure de départ</th>
                                        <th class="px-6 py-3 text-center">Statut du pointage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-variant bg-white">
                                    @foreach($this->pointages as $p)
                                        @php
                                            $hasArrivee = !empty($p->heure_arrivee);
                                            $hasDepart = !empty($p->heure_depart);
                                        @endphp
                                        <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                            <td class="px-6 py-4 font-semibold text-primary">
                                                {{ \Carbon\Carbon::parse($p->date)->translatedFormat('l j F Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono font-bold text-primary">
                                                {{ $hasArrivee ? substr((string)$p->heure_arrivee, 0, 5) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono font-bold text-primary">
                                                {{ $hasDepart ? substr((string)$p->heure_depart, 0, 5) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($hasArrivee && $hasDepart)
                                                    <flux:badge color="green">Complet</flux:badge>
                                                @elseif($hasArrivee)
                                                    <flux:badge color="blue">En cours (Arrivé)</flux:badge>
                                                @else
                                                    <flux:badge color="zinc">Non renseigné</flux:badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </flux:card>
                @endif
            @endif
        </div>
    </div>
</div>
