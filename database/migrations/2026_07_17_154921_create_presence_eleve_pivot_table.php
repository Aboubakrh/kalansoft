<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presence_eleves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presence_id')->constrained('presences')->cascadeOnDelete();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->string('statut', 15)->nullable();
            $table->unique(['presence_id', 'eleve_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_eleves');
    }
};
