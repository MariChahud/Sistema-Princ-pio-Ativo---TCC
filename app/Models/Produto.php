<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'dcb', // pros farmaceuticos
        'unidade',
        'preco_base',
        'estoque_minimo',
        'estoque_atual',
    ];

    protected $casts = [
        'preco_base'     => 'decimal:2',
        'estoque_minimo' => 'integer',
        'estoque_atual'  => 'decimal:4', 
    ];

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function abaixoDoMinimo(): bool
    {
        return $this->estoque_atual < $this->estoque_minimo;
    }

    // lotes com validades proxima, padraso é 30 dias
    public function lotesValidadeProxima()
    {
        return $this->lotes()
            ->where('ativo', true)
            ->whereDate('validade', '>', now())
            ->whereDate('validade', '<=', now()->addDays(30))
            ->get();
    }
}