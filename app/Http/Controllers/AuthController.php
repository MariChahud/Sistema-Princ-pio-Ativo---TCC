<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //tela de login
    public function showLogin()
    {
        return view('auth.login');
    }

    //tentativa de login do usuario
    public function login(Request $request)
    {
        // valida se os campos foram preenchidos corretamente antes de testar no banco
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // tenta autenticar com a opção de lembrar 
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Se der certo, regenera a sessão por segurança
            $request->session()->regenerate();

            // Redireciona para onde ele tentou entrar ou para o dashboard por padrão
            return redirect()->intended(route('dashboard'));
        }

        // se as credenciais estiverem erradas, volta com o erro na tela
        return back()
            ->withErrors(['email' => 'Campos incorretos'])
            ->onlyInput('email'); // deixa o email preenchido
    }

    //desloga o usuario
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}