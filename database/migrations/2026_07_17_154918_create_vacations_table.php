<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnels')->cascadeOnDelete();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('nombre_heures', 4, 2);
            $table->decimal('montant', 10, 2);
            $table->string('etat', 30)->default('En attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
