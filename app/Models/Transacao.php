<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes'; // Garante que o Laravel busque a tabela correta com acento/plural adaptado

    protected $fillable = [
        'tipo',           // entrada | saida
        'descricao',
        'valor',
        'data',
        'categoria',      // vendas | fornecedores | salarios | aluguel | utilidades | outros
        'forma_pagamento',// dinheiro | pix | cartao | boleto | transferencia
        'cliente_id',
        'receita_id',
    ];

    protected $casts = [
        'data'  => 'date',
        'valor' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function receita(): BelongsTo
    {
        return $this->belongsTo(Receita::class);
    }
}