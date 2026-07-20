<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained('inscriptions')->cascadeOnDelete();
            $table->integer('trimestre');
            $table->decimal('moyenne', 5, 2);
            $table->integer('rang')->nullable();
            $table->foreignId('decision_id')->nullable()->constrained('decisions')->nullOnDelete();
            $table->foreignId('appreciation_id')->nullable()->constrained('appreciations')->nullOnDelete();
            $table->date('date_generation')->useCurrent();
            $table->unique(['inscription_id', 'trimestre']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
