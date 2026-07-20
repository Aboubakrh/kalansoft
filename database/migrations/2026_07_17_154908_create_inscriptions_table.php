<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->restrictOnDelete();
            $table->date('date_inscription')->useCurrent();
            $table->decimal('frais_scolarite', 10, 2);
            $table->decimal('remise', 10, 2)->default(0);
            $table->string('statut', 20)->default('Inscrit');
            $table->text('observation')->nullable();
            $table->unique(['eleve_id', 'annee_scolaire_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
