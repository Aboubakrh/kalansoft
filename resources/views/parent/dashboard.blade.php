<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <!-- Header & Child Selector -->
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Espace Famille</h2>
                <p class="font-body-lg text-secondary mt-1">Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->nom }}</p>
            </div>
            
            @if($this->eleves->isNotEmpty())
            <flux:dropdown>
                <flux:button icon-trailing="chevron-down" class="w-full md:w-auto text-left">
                    Sélectionner un enfant : <strong class="ml-1">{{ $this->selectedEleve ? $this->selectedEleve->user?->prenom : 'Aucun' }}</strong>
                </flux:button>
                <flux:menu>
                    @foreach($this->eleves as $eleve)
                        <flux:menu.item wire:click="selectEleve({{ $eleve->id }})">
                            {{ $eleve->user?->prenom }} {{ $eleve->user?->nom }} 
                            @if($eleve->inscriptions->first())
                                ({{ $eleve->inscriptions->first()->classe->nom }})
                            @endif
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>
            @endif
        </header>

        @if($this->selectedEleve)
        <!-- Bento Grid Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Academic Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                <div>
                    @php
                        $latestBulletin = $this->currentInscription ? $this->currentInscription->bulletins->sortByDesc('trimestre')->first() : null;
                    @endphp
                    <h3 class="font-title-md text-secondary mb-2">
                        Moyenne Générale {{ $latestBulletin ? '(Trimestre '.$latestBulletin->trimestre.')' : '' }}
                    </h3>
                    <div class="flex items-baseline gap-3 mb-1">
                        <span class="font-display-lg text-[48px] font-bold text-primary">
                            {{ $latestBulletin ? number_format($latestBulletin->moyenne, 2, ',', ' ') : '--' }}
                        </span>
                        <span class="font-body-lg text-secondary">/20</span>
                    </div>
                    @if($latestBulletin && $latestBulletin->appreciation_id)
                        <flux:badge color="zinc" class="mt-2 text-primary border border-surface-variant">Mention: {{ $latestBulletin->appreciation->libelle ?? 'N/A' }}</flux:badge>
                    @endif
                </div>
                <div class="mt-8">
                    @if($latestBulletin)
                    <flux:button href="{{ route('parent.bulletins.download', ['inscription' => $this->currentInscription->id, 'periode' => $latestBulletin->trimestre]) }}" target="_blank" class="w-full sm:w-auto">Voir le bulletin détaillé</flux:button>
                    @else
                    <span class="text-sm text-secondary">Aucun bulletin disponible pour le moment.</span>
                    @endif
                </div>
            </flux:card>

            <!-- Financial Card -->
            <flux:card class="flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                <div>
                    <h3 class="font-title-md text-secondary mb-2">Situation Financière</h3>
                    @if($this->currentInscription)
                        @php
                            $soldeRestant = $this->currentInscription->soldeRestant();
                        @endphp
                        @if($soldeRestant > 0)
                            <p class="font-body-lg text-primary mb-4">Reste à payer pour l'année en cours</p>
                            <div class="font-display-lg text-[48px] font-bold text-primary flex items-baseline gap-2">
                                {{ number_format($soldeRestant, 0, ',', ' ') }} <span class="font-title-md text-[20px] text-secondary font-normal">FCFA</span>
                            </div>
                        @else
                            <p class="font-body-lg text-success mb-4">Scolarité totalement réglée</p>
                            <div class="font-display-lg text-[48px] font-bold text-success flex items-baseline gap-2">
                                0 <span class="font-title-md text-[20px] text-secondary font-normal">FCFA</span>
                            </div>
                        @endif
                    @else
                        <p class="font-body-lg text-secondary mb-4">Aucune inscription active.</p>
                    @endif
                </div>
                @if($this->currentInscription && $this->currentInscription->soldeRestant() > 0)
                <div class="mt-4 pt-4 border-t border-surface-variant flex justify-end">
                    <flux:button variant="primary" size="sm" href="{{ route('parent.paiements') }}">Payer maintenant</flux:button>
                </div>
                @endif
            </flux:card>
        </div>

        <!-- Latest Grades & School Life -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Dernières Notes Obtenues (Static placeholder for now, requires fetching individual notes if applicable) -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Dernières Notes Obtenues</flux:heading>
                <div class="space-y-4">
                    @php
                        // Here we could fetch actual recent notes if the logic requires it.
                        // For the parent dashboard MVP, we'll keep the static design unless $this->selectedEleve->notes exists.
                        $recentNotes = $this->selectedEleve->notes ?? collect(); 
                    @endphp
                    
                    @if($recentNotes->isEmpty())
                        <div class="text-sm text-secondary italic">Aucune note récente enregistrée.</div>
                    @else
                        @foreach($recentNotes->take(4) as $note)
                        <div class="flex justify-between items-center p-3 hover:bg-surface-container-low rounded-lg transition-colors border border-transparent hover:border-outline-variant">
                            <span class="font-body-lg text-primary font-medium">{{ $note->evaluation->cours->matiere->nom ?? 'Matière' }}</span>
                            <div class="flex items-center gap-3">
                                <span class="font-title-md text-primary font-bold">{{ $note->valeur }}/20</span>
                            </div>
                        </div>
                        <div class="w-full h-px bg-outline-variant/50"></div>
                        @endforeach
                    @endif
                </div>
            </flux:card>

            <!-- Vie Scolaire (Attendance) -->
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4 pb-4 border-b border-surface-variant">Vie Scolaire</flux:heading>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Date & Heure</flux:table.column>
                        <flux:table.column>Type d'incident</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        <flux:table.row>
                            <flux:table.cell class="text-secondary text-sm italic" colspan="2">Aucune absence ou retard signalé.</flux:table.cell>
                        </flux:table.row>
                    </flux:table.rows>
                </flux:table>
                
                <div class="mt-6 flex justify-end">
                    <flux:link href="#" class="text-sm flex items-center gap-1 text-secondary hover:text-primary">
                        Voir tout l'historique
                        <flux:icon.arrow-right class="size-4" />
                    </flux:link>
                </div>
            </flux:card>
        </div>
        @else
        <div class="text-center p-12 bg-white rounded-xl shadow-sm">
            <p class="text-secondary">Aucun enfant n'est lié à votre compte actuellement.</p>
        </div>
        @endif
    </div>
</div>
