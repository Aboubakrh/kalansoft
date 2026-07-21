<?php

declare(strict_types=1);

namespace App\Livewire\Parent;

use Livewire\Component;
use App\Models\Finance\Paiement as PaiementModel;
use Livewire\Attributes\Validate;

class Paiement extends Component
{
    use Traits\HasEleveSelector;

    #[Validate('required|numeric|min:100')]
    public $montant;

    #[Validate('required|in:Orange Money,Moov Money,Carte Bancaire')]
    public $mode_paiement = 'Orange Money';

    #[Validate('required|string|min:8')]
    public $telephone_ou_carte;

    // Reset amount when child changes
    public function updatedSelectedEleveId()
    {
        $this->setDefaultMontant();
    }

    public function mount()
    {
        // Trait's mount replacement is handled automatically if we call it
        // but we named it differently, so we must call it.
        $this->mountHasEleveSelector();
        $this->setDefaultMontant();
    }

    public function setDefaultMontant()
    {
        $inscription = $this->currentInscription;
        if ($inscription) {
            $this->montant = $inscription->soldeRestant() > 0 ? $inscription->soldeRestant() : null;
        } else {
            $this->montant = null;
        }
    }

    public function traiterPaiement()
    {
        $this->validate();

        $inscription = $this->currentInscription;
        if (!$inscription) {
            $this->addError('montant', 'Aucune inscription active trouvée.');
            return;
        }

        $resteAPayer = $inscription->soldeRestant();
        if ($this->montant > $resteAPayer) {
            $this->addError('montant', 'Le montant ne peut pas dépasser le reste à payer ('.$resteAPayer.' FCFA).');
            return;
        }

        // Créer le paiement simulé
        PaiementModel::create([
            'inscription_id' => $inscription->id,
            'caissier_id' => auth()->id(), // Utilisateur connecté (Parent)
            'montant' => $this->montant,
            'numero_recu' => 'PAY-ONL-' . time() . '-' . rand(100, 999),
            'mode_paiement' => $this->mode_paiement,
            'intitule_tranche' => 'Paiement en ligne',
            'observation' => 'Paiement simulé par le parent via ' . $this->mode_paiement . ' ('.$this->telephone_ou_carte.')',
        ]);

        // Mettre à jour l'état de l'inscription si nécessaire, 
        // ou rafraîchir les modèles chargés (car PaiementModel::create ne rafraîchit pas l'instance courante)
        $this->parentEleve()->unsetRelation('eleves'); // Force refresh of eleves
        $this->setDefaultMontant();
        $this->reset('telephone_ou_carte');

        // Notifier le parent du succès
        session()->flash('success', 'Votre paiement de ' . $this->montant . ' FCFA a été traité avec succès (Simulation).');
    }

    public function render()
    {
        return view('livewire.parent.paiement')
            ->layout('components.layouts.dashboard', ['title' => __('Scolarité & Paiements')]);
    }
}
