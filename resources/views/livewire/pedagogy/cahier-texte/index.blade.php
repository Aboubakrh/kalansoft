<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Page Header -->
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="font-headline-lg text-[24px] text-primary">Cahier de Texte (Supervision)</h2>
                    <p class="font-body-lg text-secondary mt-1">Consultez l'avancement du programme et les travaux donnés par les professeurs.</p>
                </div>
            </div>

            <!-- Toolbar -->
            <flux:card class="p-4 flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div class="flex flex-col gap-1 w-full sm:w-64">
                        <flux:select wire:model.live="classe_id" label="Classe">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->nom }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="flex flex-col gap-1 w-full sm:w-64">
                        <flux:select wire:model.live="matiere_id" label="Matière">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $m)
                                <option value="{{ $m->id }}">{{ $m->nom }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <flux:button variant="ghost" icon="funnel" class="w-full md:w-auto">Filtrer</flux:button>
            </flux:card>

            <!-- Timeline Area -->
            <div class="relative mt-8 pl-8 md:pl-12">
                <!-- Vertical Line -->
                <div class="absolute left-4 md:left-6 top-0 bottom-0 w-[2px] bg-surface-variant opacity-50"></div>
                
                <!-- Timeline Items -->
                <div class="flex flex-col gap-8">
                    @forelse($cahiers as $cahier)
                    <div class="relative">
                        <!-- Dot -->
                        <div class="absolute -left-[39px] md:-left-[47px] top-6 w-4 h-4 bg-primary rounded-full border-4 border-surface shadow-sm"></div>
                        
                        <!-- Card -->
                        <flux:card class="p-6 transition-shadow duration-300 hover:shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-surface-variant/30 text-on-surface px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide">
                                    {{ \Carbon\Carbon::parse($cahier->date)->translatedFormat('l d F Y') }}
                                </span>
                            </div>
                            
                            <h4 class="text-xl font-bold text-primary mb-1">{{ $cahier->chapitre }}</h4>
                            <div class="text-xs text-secondary mb-3 font-semibold">{{ $cahier->cours->matiere->nom ?? '' }} - {{ $cahier->cours->classe->nom ?? '' }}</div>
                            
                            <p class="text-secondary leading-relaxed mb-6 whitespace-pre-line">
                                {{ $cahier->contenu }}
                            </p>
                            
                            @if($cahier->travail_maison)
                            <div class="bg-surface-variant/20 p-4 rounded-xl border border-surface-variant border-dashed mb-4 flex gap-3 items-start">
                                <flux:icon.clipboard-document-list class="size-5 text-secondary mt-0.5" />
                                <div>
                                    <h5 class="text-xs font-semibold text-primary mb-1 uppercase tracking-wide">Travail à faire</h5>
                                    <p class="text-sm text-secondary whitespace-pre-line">{{ $cahier->travail_maison }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="pt-4 border-t border-surface-variant flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-surface-variant/50 flex items-center justify-center text-primary text-xs font-bold uppercase">
                                    {{ substr($cahier->cours->enseignant->user->prenom ?? '', 0, 1) }}{{ substr($cahier->cours->enseignant->user->nom ?? '', 0, 1) }}
                                </div>
                                <span class="text-sm text-primary font-semibold">
                                    {{ $cahier->cours->enseignant->user->prenom ?? '' }} {{ $cahier->cours->enseignant->user->nom ?? '' }}
                                </span>
                            </div>
                        </flux:card>
                    </div>
                    @empty
                    <div class="text-center py-12 text-secondary">
                        <flux:icon.document-text class="size-12 mx-auto mb-4 opacity-50" />
                        <p class="text-lg font-medium">Aucun enregistrement trouvé</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
