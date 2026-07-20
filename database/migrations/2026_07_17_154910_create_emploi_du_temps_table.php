<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emplois_du_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->foreignId('salle_id')->constrained('salles')->restrictOnDelete();
            $table->string('jour', 15)->nullable();
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emplois_du_temps');
    }
};
