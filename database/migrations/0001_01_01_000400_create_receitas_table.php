<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('nome_formula');
            $table->string('medico');
            $table->string('crm');
            $table->date('data');
            $table->enum('status', ['aguardando_pesagem', 'pesado', 'finalizado', 'cancelado'])
                  ->default('aguardando_pesagem');
            $table->decimal('orcamento', 10, 2)->default(0);
            $table->integer('qtd_capsulas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};