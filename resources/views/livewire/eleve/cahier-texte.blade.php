<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <header class="mb-8">
                <h2 class="font-headline-lg text-[24px] text-primary">Mes Devoirs & Leçons</h2>
                <p class="font-body-lg text-secondary mt-1">Consultez le cahier de textes de votre classe.</p>
            </header>

            @if($this->inscriptionActive)
                @if($this->devoirs->isEmpty())
                    <flux:card class="p-12 text-center bg-gray-50 border-gray-200">
                        <flux:icon.book-open class="size-8 mx-auto text-gray-400 mb-3" />
                        <p class="text-secondary">Aucun devoir ou leçon n'a été enregistré par vos professeurs.</p>
                    </flux:card>
                @else
                    <div class="space-y-6">
                        @foreach($this->devoirs as $devoir)
                            @php
                                $isDevoir = !empty($devoir->date_remise);
                                $isLate = $isDevoir && \Carbon\Carbon::parse($devoir->date_remise)->isPast();
                            @endphp
                            <flux:card class="p-6 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1.5 h-full {{ $isDevoir ? 'bg-orange-500' : 'bg-blue-500' }}"></div>
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <flux:badge size="sm" color="{{ $isDevoir ? 'orange' : 'blue' }}">
                                                {{ $isDevoir ? 'Devoir / À rendre' : 'Leçon' }}
                                            </flux:badge>
                                            <span class="text-sm font-semibold text-primary">{{ $devoir->cours->matiere->nom }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-primary">{{ $devoir->titre }}</h3>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-secondary">
                                            Donné le {{ \Carbon\Carbon::parse($devoir->date_saisie)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-secondary mt-1">
                                            par {{ $devoir->cours->enseignant->user->prenom ?? '' }} {{ $devoir->cours->enseignant->user->nom ?? '' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg text-secondary text-sm prose max-w-none">
                                    {!! nl2br(e($devoir->contenu)) !!}
                                </div>

                                @if($isDevoir)
                                    <div class="mt-4 flex items-center gap-2 {{ $isLate ? 'text-red-600' : 'text-orange-600' }} font-medium text-sm">
                                        <flux:icon.clock class="size-4" />
                                        À rendre pour le : {{ \Carbon\Carbon::parse($devoir->date_remise)->translatedFormat('l j F Y') }}
                                        @if($isLate)
                                            (En retard)
                                        @endif
                                    </div>
                                @endif
                            </flux:card>
                        @endforeach
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
