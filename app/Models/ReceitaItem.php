<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceitaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receita_id',
        'produto_id',
        'lote_id',
        'dosagem_mg',
        'peso_real',
    ];

    protected $casts = [
        'dosagem_mg' => 'decimal:4',
        'peso_real'  => 'decimal:4',
    ];

    public function receita(): BelongsTo
    {
        return $this->belongsTo(Receita::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    // peso teórico em gramas = (dosagem_mg / 1000) * qtd de cápsulas da receita
    public function pesoTeorico(): float
    {
        return ($this->dosagem_mg / 1000) * ($this->receita->qtd_capsulas ?? 0);
    }
}