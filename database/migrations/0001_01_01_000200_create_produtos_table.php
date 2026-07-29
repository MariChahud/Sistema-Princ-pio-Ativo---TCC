<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('dcb')->comment('Denominação Comum Brasileira');
            $table->enum('unidade', ['g', 'mg', 'ml', 'un'])->default('g');
            $table->decimal('preco_base', 10, 2)->default(0);
            $table->integer('estoque_minimo')->default(0);
            $table->decimal('estoque_atual', 12, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};