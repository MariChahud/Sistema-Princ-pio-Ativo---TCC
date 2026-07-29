<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lote extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'numero',
        'quantidade',
        'validade',
        'fornecedor',
        'cnpj_fornecedor',
        'ativo',
    ];

    protected $casts = [
        'validade'   => 'date',
        'ativo'      => 'boolean',
        'quantidade' => 'decimal:4',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function diasParaVencer(): int
    {
        return (int) ceil(now()->diffInDays($this->validade, false));
    }
}