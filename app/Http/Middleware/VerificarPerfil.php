<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPerfil
{
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