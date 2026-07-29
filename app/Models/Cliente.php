<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
    ];

    // relacao de clientes e receitas - 1 pra n
    public function receitas(): HasMany
    {
        return $this->hasMany(Receita::class);
    }

    //relacao clientes e compras que ele faz na farmacia, 1 pra n
    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class);
    }

    // relacao do total de gastos que um cliente tem na farmacia, fica no financeiro
    public function totalGasto(): float
    {
        return (float) $this->transacoes()
            ->where('tipo', 'entrada')
            ->sum('valor');
    }
}