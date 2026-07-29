<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    //permissoes dos perfis de acesso
   public const PERMISSOES = [
    'admin'        => ['dashboard', 'usuarios', 'clientes', 'produtos', 'receitas', 'financeiro'],
    'farmaceutico' => ['dashboard', 'clientes', 'produtos', 'receitas', 'financeiro'],
    'balconista'   => ['dashboard', 'clientes', 'financeiro'],
];

    //dados do usuario
    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'perfil',
        'crf',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->nome;
    }

    public function podeAcessar(string $modulo): bool
    {
        return in_array($modulo, self::PERMISSOES[$this->perfil] ?? [], true);
    }

    public function perfilLabel(): string
    {
        return [
            'admin'        => 'Administrador',
            'farmaceutico' => 'Farmacêutico',
            'balconista'   => 'Balconista',
        ][$this->perfil] ?? $this->perfil;
    }
}