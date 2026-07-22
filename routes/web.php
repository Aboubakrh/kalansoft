<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Pedagogy\BulletinController;
use App\Livewire\Classes\Edit;
use App\Livewire\Eleve\Absences;
use App\Livewire\Eleve\Notes;
use App\Livewire\Eleves\Create;
use App\Livewire\Eleves\Index;
use App\Livewire\Finances\Encaisser;
use App\Livewire\Parent\Bulletin;
use App\Livewire\Parent\DossierScolaire;
use App\Livewire\Parent\Paiement;
use App\Livewire\Pedagogy\Notes\Saisie;
use App\Livewire\Professeur\CahierTexte;
use App\Livewire\Professeur\Classes;
use App\Livewire\Professeur\Dashboard;
use App\Livewire\Professeur\EmploiDuTemps;
use App\Livewire\Professeur\Presence;
use App\Livewire\Professeurs\Show;
use App\Livewire\RH\Pointage;
use App\Livewire\RH\Vacations;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Élèves
    Route::get('eleves', Index::class)->name('admin.eleves.index');
    Route::get('eleves/create', Create::class)->name('admin.eleves.create');
    // Salles & Classes
    Route::get('classes', App\Livewire\Classes\Index::class)->name('admin.classes.index');
    Route::get('classes/create', App\Livewire\Classes\Create::class)->name('admin.classes.create');
    Route::get('classes/{classe}/edit', Edit::class)->name('admin.classes.edit');

    Route::get('salles', App\Livewire\Salles\Index::class)->name('admin.salles.index');
    Route::get('salles/create', App\Livewire\Salles\Create::class)->name('admin.salles.create');
    Route::get('salles/{salle}/edit', App\Livewire\Salles\Edit::class)->name('admin.salles.edit');

    // Professeurs
    Route::get('professeurs', App\Livewire\Professeurs\Index::class)->name('admin.professeurs.index');
    Route::get('professeurs/create', App\Livewire\Professeurs\Create::class)->name('admin.professeurs.create');
    Route::get('professeurs/{personnel}', Show::class)->name('admin.professeurs.show');
    Route::get('professeurs/{personnel}/edit', App\Livewire\Professeurs\Edit::class)->name('admin.professeurs.edit');

    // Finances
    Route::get('finances/paiements', App\Livewire\Finances\Index::class)->name('admin.finances.paiements');
    Route::get('finances/encaisser', Encaisser::class)->name('admin.finances.encaisser');
    Route::get('finances/frais-scolaires', App\Livewire\Finances\FraisScolaires\Index::class)->name('admin.finances.frais');

    // Pédagogie (Admin only parts)
    Route::get('pedagogie/matieres', App\Livewire\Pedagogy\Matieres\Index::class)->name('admin.matieres.index');
    Route::get('pedagogie/matieres/create', App\Livewire\Pedagogy\Matieres\Create::class)->name('admin.matieres.create');
    Route::get('pedagogie/matieres/{matiere}/edit', App\Livewire\Pedagogy\Matieres\Edit::class)->name('admin.matieres.edit');

    Route::get('pedagogie/cours', App\Livewire\Pedagogy\Cours\Index::class)->name('admin.cours.index');
    Route::get('pedagogie/cours/create', App\Livewire\Pedagogy\Cours\Create::class)->name('admin.cours.create');
    Route::get('pedagogie/cours/{cours}/edit', App\Livewire\Pedagogy\Cours\Edit::class)->name('admin.cours.edit');

    Route::get('pedagogie/emplois-du-temps', App\Livewire\Pedagogy\EmploisDuTemps\Index::class)->name('admin.emplois-du-temps.index');
    Route::get('pedagogie/cahier-texte', App\Livewire\Pedagogy\CahierTexte\Index::class)->name('admin.cahier-texte.index');
    Route::get('pedagogie/coefficients', App\Livewire\Pedagogy\Coefficients\Index::class)->name('admin.coefficients.index');
    Route::get('pedagogie/bulletins', App\Livewire\Pedagogy\Bulletins\Index::class)->name('admin.bulletins.index');
    Route::get('pedagogie/bulletins/{inscription}/download/{periode}', [BulletinController::class, 'download'])->name('admin.bulletins.download');

    // Ressources Humaines
    Route::get('rh/personnel', App\Livewire\RH\Index::class)->name('admin.rh.index');
    Route::get('rh/pointage', Pointage::class)->name('admin.rh.pointage');
    Route::get('rh/vacations', Vacations::class)->name('admin.rh.vacations');

    // Paramètres
    Route::get('parametres', App\Livewire\Settings\Index::class)->name('admin.settings.index');
    Route::get('parametres/series', App\Livewire\Settings\Series\Index::class)->name('admin.settings.series');
    Route::get('parametres/annees', App\Livewire\Settings\AnneeScolaires\Index::class)->name('admin.settings.annees');
});

// Pédagogie partagée (Admin & Professeur)
Route::middleware(['auth', 'verified', 'role:admin|professeur'])->group(function () {
    Route::get('pedagogie/evaluations', App\Livewire\Pedagogy\Evaluations\Index::class)->name('admin.evaluations.index');
    Route::get('pedagogie/evaluations/create', App\Livewire\Pedagogy\Evaluations\Create::class)->name('admin.evaluations.create');
    Route::get('pedagogie/evaluations/{evaluation}/edit', App\Livewire\Pedagogy\Evaluations\Edit::class)->name('admin.evaluations.edit');

    Route::get('pedagogie/notes/{evaluation_id}', Saisie::class)->name('admin.notes.saisie');
});

// Professeur
Route::middleware(['auth', 'verified', 'role:professeur'])->group(function () {
    Route::get('professeur/dashboard', Dashboard::class)->name('professeur.dashboard');
    Route::get('professeur/classes', Classes::class)->name('professeur.classes');
    Route::get('professeur/emploi-du-temps', EmploiDuTemps::class)->name('professeur.emploi-du-temps');
    Route::get('professeur/cahier-texte', CahierTexte::class)->name('professeur.cahier-texte');
    Route::get('professeur/presences', Presence::class)->name('professeur.presences');
    Route::get('professeur/pointages', App\Livewire\Professeur\Pointage::class)->name('professeur.pointages');
});

// Parent
Route::middleware(['auth', 'verified', 'role:parent'])->group(function () {
    Route::get('parent/dashboard', App\Livewire\Parent\Dashboard::class)->name('parent.dashboard');
    Route::get('parent/dossier-scolaire', DossierScolaire::class)->name('parent.dossier-scolaire');
    Route::get('parent/emploi-du-temps', App\Livewire\Parent\EmploiDuTemps::class)->name('parent.emploi-du-temps');
    Route::get('parent/bulletins-notes', Bulletin::class)->name('parent.bulletins-notes');
    Route::get('parent/paiements', Paiement::class)->name('parent.paiements');
    Route::get('parent/bulletins/{inscription}/download/{periode}', [BulletinController::class, 'download'])->name('parent.bulletins.download');
});

// Eleve
Route::middleware(['auth', 'verified', 'role:eleve'])->group(function () {
    Route::get('eleve/dashboard', App\Livewire\Eleve\Dashboard::class)->name('eleve.dashboard');
    Route::get('eleve/notes', Notes::class)->name('eleve.notes');
    Route::get('eleve/emploi-du-temps', App\Livewire\Eleve\EmploiDuTemps::class)->name('eleve.emploi-du-temps');
    Route::get('eleve/cahier-texte', App\Livewire\Eleve\CahierTexte::class)->name('eleve.cahier-texte');
    Route::get('eleve/absences', Absences::class)->name('eleve.absences');
});

require __DIR__.'/settings.php';
