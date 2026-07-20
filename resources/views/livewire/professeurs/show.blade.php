<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Détails du Professeur</h2>
                <p class="font-body-lg text-secondary mt-1">Vue d'ensemble de {{ $personnel->user->name }}</p>
            </div>
            <div class="flex gap-2">
                <flux:button variant="ghost" icon="arrow-left" href="{{ route('admin.professeurs.index') }}">Retour</flux:button>
                <flux:button variant="primary" icon="pencil" href="{{ route('admin.professeurs.edit', $personnel->id) }}">Modifier</flux:button>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informations -->
            <flux:card class="p-6 md:col-span-1 h-fit">
                <flux:heading size="lg" class="mb-4">Profil</flux:heading>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-secondary">Nom Complet</div>
                        <div class="font-medium text-primary">{{ $personnel->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-secondary">Email</div>
                        <div class="font-medium text-primary">{{ $personnel->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-secondary">Matricule</div>
                        <div class="font-medium text-primary">{{ $personnel->matricule }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-secondary">Statut</div>
                        <flux:badge size="sm" color="{{ $personnel->statut === 'Actif' ? 'success' : 'zinc' }}">{{ $personnel->statut ?? 'Inconnu' }}</flux:badge>
                    </div>
                    @if($personnel->date_embauche)
                    <div>
                        <div class="text-sm text-secondary">Date d'embauche</div>
                        <div class="font-medium text-primary">{{ \Carbon\Carbon::parse($personnel->date_embauche)->format('d/m/Y') }}</div>
                    </div>
                    @endif
                </div>
            </flux:card>

            <!-- Cours assignés -->
            <flux:card class="p-6 md:col-span-2">
                <flux:heading size="lg" class="mb-4">Cours Assignés ({{ $cours->count() }})</flux:heading>
                
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Classe</flux:table.column>
                        <flux:table.column>Matière</flux:table.column>
                        <flux:table.column>Taux Horaire</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @forelse($cours as $c)
                            <flux:table.row>
                                <flux:table.cell class="font-bold text-primary">{{ $c->classe->nom }}</flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $c->matiere->couleur ?? '#CBD5E1' }}"></div>
                                        <span>{{ $c->matiere->nom }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>{{ $c->taux_horaire_vacation > 0 ? number_format($c->taux_horaire_vacation, 0) . ' F/h' : '-' }}</flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="3" class="text-center text-secondary py-4">Aucun cours assigné à ce professeur.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>
    </div>
</div>
