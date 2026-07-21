<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coefficients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('series')->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->integer('valeur');
            $table->unique(['serie_id', 'matiere_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coefficients');
    }
};
