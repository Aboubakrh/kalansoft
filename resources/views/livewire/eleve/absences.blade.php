<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="mb-8">
                <h2 class="font-headline-lg text-[24px] text-primary">Mes Absences & Retards</h2>
                <p class="font-body-lg text-secondary mt-1">Consultez l'historique de vos présences.</p>
            </header>

            @if($this->inscriptionActive)
                @if($this->absences->isEmpty())
                    <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                        <flux:icon.check-circle class="size-8 mx-auto text-green-500 mb-3" />
                        <p class="text-secondary">Félicitations ! Vous n'avez aucune absence ni retard enregistré.</p>
                    </flux:card>
                @else
                    <div class="space-y-6">
                        @php
                            $totalAbsences = $this->absences->filter(fn($p) => $p->eleves->first()->pivot->statut === 'Absent')->count();
                            $totalRetards = $this->absences->filter(fn($p) => $p->eleves->first()->pivot->statut === 'Retard')->count();
                        @endphp
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <flux:card class="p-6 text-center border-red-100 bg-red-50/30">
                                <h3 class="text-secondary font-medium">Total Absences</h3>
                                <div class="text-3xl font-bold text-red-600 mt-2">{{ $totalAbsences }}</div>
                            </flux:card>
                            <flux:card class="p-6 text-center border-orange-100 bg-orange-50/30">
                                <h3 class="text-secondary font-medium">Total Retards</h3>
                                <div class="text-3xl font-bold text-orange-600 mt-2">{{ $totalRetards }}</div>
                            </flux:card>
                        </div>

                        <flux:card class="p-0 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-surface-container-lowest border-b border-surface-variant text-secondary font-label-caps text-xs uppercase">
                                        <tr>
                                            <th class="px-6 py-3">Date</th>
                                            <th class="px-6 py-3">Cours</th>
                                            <th class="px-6 py-3">Professeur</th>
                                            <th class="px-6 py-3">Statut</th>
                                            <th class="px-6 py-3">Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-surface-variant bg-white">
                                        @foreach($this->absences as $presence)
                                            @php
                                                $pivot = $presence->eleves->first()->pivot;
                                                $isAbsent = $pivot->statut === 'Absent';
                                            @endphp
                                            <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                                                <td class="px-6 py-4 text-secondary whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($presence->date_appel)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 font-medium text-primary">
                                                    {{ $presence->cours->matiere->nom ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 text-secondary">
                                                    {{ $presence->cours->enseignant->user->prenom ?? '' }} {{ $presence->cours->enseignant->user->nom ?? '' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <flux:badge color="{{ $isAbsent ? 'red' : 'orange' }}">
                                                        {{ $pivot->statut }}
                                                    </flux:badge>
                                                </td>
                                                <td class="px-6 py-4 text-secondary italic text-xs">
                                                    {{ $pivot->observation ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </flux:card>
                    </div>
                @endif
            @else
                <div class="text-center p-12 bg-white rounded-xl shadow-sm">
                    <p class="text-secondary">Vous n'avez aucune inscription active cette année.</p>
                </div>
            @endif
        </div>
    </div>
</div>
