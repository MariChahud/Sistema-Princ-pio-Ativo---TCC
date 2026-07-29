<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['entrada', 'saida']);
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->enum('categoria', ['vendas', 'fornecedores', 'salarios', 'aluguel', 'utilidades', 'outros'])
                  ->default('outros');
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'cartao', 'boleto', 'transferencia'])
                  ->default('dinheiro');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('receita_id')->nullable()->constrained('receitas')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacoes');
    }
};