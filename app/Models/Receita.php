<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receita extends Model
{
    use HasFactory;

    public const STATUS = [
        'aguardando_pesagem' => 'Aguardando Pesagem',
        'pesado'             => 'Pesado',
        'finalizado'         => 'Finalizado',
        'cancelado'          => 'Cancelado',
    ];

    protected $fillable = [
        'cliente_id',
        'nome_formula',
        'medico',
        'crm',
        'data',
        'status',
        'orcamento',
        'qtd_capsulas',
    ];

    protected $casts = [
        'data'         => 'date',
        'orcamento'    => 'decimal:2',
        'qtd_capsulas' => 'integer',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ReceitaItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function statusBadgeClass(): string
    {
        return [
            'aguardando_pesagem' => 'badge-primary',
            'pesado'             => 'badge-success',
            'finalizado'         => 'badge-secondary',
            'cancelado'          => 'badge-secondary',
        ][$this->status] ?? 'badge-secondary';
    }

    /** Uma receita pesada ou finalizada não pode mais ser editada/excluída. */
    public function estaBloqueada(): bool
    {
        return in_array($this->status, ['pesado', 'finalizado'], true);
    }
}