<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Élèves
    Route::get('eleves', \App\Livewire\Eleves\Index::class)->name('admin.eleves.index');
    Route::get('eleves/create', \App\Livewire\Eleves\Create::class)->name('admin.eleves.create');
    // Salles & Classes
    Route::get('classes', \App\Livewire\Classes\Index::class)->name('admin.classes.index');
    Route::get('classes/create', \App\Livewire\Classes\Create::class)->name('admin.classes.create');
    Route::get('classes/{classe}/edit', \App\Livewire\Classes\Edit::class)->name('admin.classes.edit');

    Route::get('salles', \App\Livewire\Salles\Index::class)->name('admin.salles.index');
    Route::get('salles/create', \App\Livewire\Salles\Create::class)->name('admin.salles.create');
    Route::get('salles/{salle}/edit', \App\Livewire\Salles\Edit::class)->name('admin.salles.edit');

    // Professeurs
    Route::get('professeurs', \App\Livewire\Professeurs\Index::class)->name('admin.professeurs.index');
    Route::get('professeurs/create', \App\Livewire\Professeurs\Create::class)->name('admin.professeurs.create');
    Route::get('professeurs/{personnel}', \App\Livewire\Professeurs\Show::class)->name('admin.professeurs.show');
    Route::get('professeurs/{personnel}/edit', \App\Livewire\Professeurs\Edit::class)->name('admin.professeurs.edit');

    
    // Finances
    Route::get('finances/paiements', \App\Livewire\Finances\Index::class)->name('admin.finances.paiements');
    Route::get('finances/encaisser', \App\Livewire\Finances\Encaisser::class)->name('admin.finances.encaisser');
    Route::get('finances/frais-scolaires', \App\Livewire\Finances\FraisScolaires\Index::class)->name('admin.finances.frais');

    // Pédagogie (Admin only parts)
    Route::get('pedagogie/matieres', \App\Livewire\Pedagogy\Matieres\Index::class)->name('admin.matieres.index');
    Route::get('pedagogie/matieres/create', \App\Livewire\Pedagogy\Matieres\Create::class)->name('admin.matieres.create');
    Route::get('pedagogie/matieres/{matiere}/edit', \App\Livewire\Pedagogy\Matieres\Edit::class)->name('admin.matieres.edit');

    Route::get('pedagogie/cours', \App\Livewire\Pedagogy\Cours\Index::class)->name('admin.cours.index');
    Route::get('pedagogie/cours/create', \App\Livewire\Pedagogy\Cours\Create::class)->name('admin.cours.create');
    Route::get('pedagogie/cours/{cours}/edit', \App\Livewire\Pedagogy\Cours\Edit::class)->name('admin.cours.edit');

    Route::get('pedagogie/emplois-du-temps', \App\Livewire\Pedagogy\EmploisDuTemps\Index::class)->name('admin.emplois-du-temps.index');
    Route::get('pedagogie/cahier-texte', \App\Livewire\Pedagogy\CahierTexte\Index::class)->name('admin.cahier-texte.index');
    Route::get('pedagogie/bulletins', \App\Livewire\Pedagogy\Bulletins\Index::class)->name('admin.bulletins.index');
    Route::get('pedagogie/bulletins/{inscription}/download/{periode}', [\App\Http\Controllers\Pedagogy\BulletinController::class, 'download'])->name('admin.bulletins.download');

    // Ressources Humaines
    Route::get('rh/personnel', \App\Livewire\RH\Index::class)->name('admin.rh.index');

    // Paramètres
    Route::get('parametres', \App\Livewire\Settings\Index::class)->name('admin.settings.index');
    Route::get('parametres/series', \App\Livewire\Settings\Series\Index::class)->name('admin.settings.series');
    Route::get('parametres/annees', \App\Livewire\Settings\AnneeScolaires\Index::class)->name('admin.settings.annees');
});

// Pédagogie partagée (Admin & Professeur)
Route::middleware(['auth', 'verified', 'role:admin|professeur'])->group(function () {
    Route::get('pedagogie/evaluations', \App\Livewire\Pedagogy\Evaluations\Index::class)->name('admin.evaluations.index');
    Route::get('pedagogie/evaluations/create', \App\Livewire\Pedagogy\Evaluations\Create::class)->name('admin.evaluations.create');
    Route::get('pedagogie/evaluations/{evaluation}/edit', \App\Livewire\Pedagogy\Evaluations\Edit::class)->name('admin.evaluations.edit');
    
    Route::get('pedagogie/notes/{evaluation_id}', \App\Livewire\Pedagogy\Notes\Saisie::class)->name('admin.notes.saisie');
});

// Professeur
Route::middleware(['auth', 'verified', 'role:professeur'])->group(function () {
    Route::get('professeur/dashboard', \App\Livewire\Professeur\Dashboard::class)->name('professeur.dashboard');
    Route::get('professeur/emploi-du-temps', \App\Livewire\Professeur\EmploiDuTemps::class)->name('professeur.emploi-du-temps');
    Route::get('professeur/cahier-texte', \App\Livewire\Professeur\CahierTexte::class)->name('professeur.cahier-texte');
});

// Parent
Route::middleware(['auth', 'verified', 'role:parent'])->group(function () {
    Route::view('parent/dashboard', 'parent.dashboard')->name('parent.dashboard');
});

// Eleve
Route::middleware(['auth', 'verified', 'role:eleve'])->group(function () {
    Route::view('eleve/dashboard', 'eleve.dashboard')->name('eleve.dashboard');
});

require __DIR__.'/settings.php';
