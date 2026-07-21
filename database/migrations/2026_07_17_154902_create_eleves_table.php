<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('matricule', 20)->unique();
            $table->date('date_naissance');
            $table->string('lieu_naissance', 100);
            $table->string('nationalite', 50)->nullable();
            $table->char('sexe', 1)->nullable();
            $table->string('groupe_sanguin', 5)->nullable();
            $table->text('antecedents_medicaux')->nullable();
            $table->string('nom_tuteur', 100);
            $table->string('telephone_tuteur', 20);
            $table->string('adresse_tuteur', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
