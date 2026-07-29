<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPerfil
{
    /**
     * Garante que o usuário autenticado pode acessar o módulo informado.
     * Substitui as funções temPermissao()/checkAuth() do antigo app.js.
     *
     * Uso na rota: ->middleware('perfil:produtos')
     */
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        $user = $request->user();

        // Se não houver usuário ou se o perfil dele não puder acessar o módulo, bloqueia
        if (! $user || ! $user->podeAcessar($modulo)) {
            abort(403, 'Você não tem permissão para acessar este módulo.');
        }

        return $next($request);
    }
}