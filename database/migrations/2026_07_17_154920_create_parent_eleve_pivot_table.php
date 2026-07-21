<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_eleves', function (Blueprint $table) {
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->string('lien_parente', 30);
            $table->primary(['parent_id', 'eleve_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_eleves');
    }
};
