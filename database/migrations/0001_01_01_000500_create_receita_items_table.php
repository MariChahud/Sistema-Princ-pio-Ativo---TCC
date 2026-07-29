<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receita_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receita_id')->constrained('receitas')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->foreignId('lote_id')->constrained('lotes')->cascadeOnDelete();
            $table->decimal('dosagem_mg', 12, 4);
            $table->decimal('peso_real', 12, 4)->nullable(); // Preenchido no momento da pesagem física
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receita_items');
    }
};