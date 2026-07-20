<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained('inscriptions')->restrictOnDelete();
            $table->foreignId('caissier_id')->constrained('users')->restrictOnDelete();
            $table->decimal('montant', 10, 2);
            $table->timestamp('date_paiement')->useCurrent();
            $table->string('numero_recu', 100)->unique();
            $table->string('mode_paiement', 30);
            $table->string('intitule_tranche', 50);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
