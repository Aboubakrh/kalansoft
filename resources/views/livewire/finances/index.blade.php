<div>
    <div class="flex-1 p-4 md:p-6 xl:p-8 overflow-y-auto no-scrollbar">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-headline-lg text-[24px] text-primary">Historique des Paiements</h2>
                <p class="font-body-lg text-secondary mt-1">Liste des paiements de scolarité enregistrés</p>
            </div>
            <flux:button variant="primary" icon="plus" href="{{ route('admin.finances.encaisser') }}">Nouvel Encaissement</flux:button>
        </header>

        <flux:card class="p-6">
            <div class="mb-6 w-1/3">
                <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Rechercher par élève ou N° de reçu..." />
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>N° Reçu</flux:table.column>
                    <flux:table.column>Élève & Classe</flux:table.column>
                    <flux:table.column>Tranche / Motif</flux:table.column>
                    <flux:table.column>Montant</flux:table.column>
                    <flux:table.column>Mode & Date</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($paiements as $paiement)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-primary">{{ $paiement->numero_recu }}</flux:table.cell>
                            <flux:table.cell>
                                <div>{{ $paiement->inscription->eleve->user->name }}</div>
                                <div class="text-sm text-secondary">{{ $paiement->inscription->classe->nom ?? 'N/A' }}</div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $paiement->intitule_tranche }}</flux:table.cell>
                            <flux:table.cell class="font-bold">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</flux:table.cell>
                            <flux:table.cell>
                                <div><flux:badge size="sm" color="zinc">{{ $paiement->mode_paiement }}</flux:badge></div>
                                <div class="text-xs text-secondary mt-1">{{ \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('d M Y à H:i') }}</div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-secondary py-4">Aucun paiement trouvé.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $paiements->links() }}
            </div>
        </flux:card>
    </div>
</div>
