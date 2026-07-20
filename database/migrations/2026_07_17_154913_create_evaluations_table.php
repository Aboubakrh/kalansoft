<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->cascadeOnDelete();
            $table->string('periode', 15);
            $table->string('type_evaluation', 15);
            $table->date('date_evaluation');
            $table->decimal('bareme', 5, 2)->default(20.00);
            $table->decimal('coefficient', 4, 2)->default(1.00);
            $table->string('code_evaluation', 50)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
