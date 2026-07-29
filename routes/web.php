<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Visitantes (não logados) acessam a tela e o processamento de Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Deslogar exige que o usuário esteja autenticado
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas autenticadas (cada módulo é protegido pelo perfil do usuário)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('perfil:dashboard')
        ->name('dashboard');

    // Usuários (Apenas Administrador)
    Route::middleware('perfil:usuarios')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });

    // Clientes (Acessado por Balconistas, Farmacêuticos e Admins)
    Route::middleware('perfil:clientes')->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });

    // Produtos e Lotes (Controle de Estoque e Insumos)
    Route::middleware('perfil:produtos')->group(function () {
        Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
        Route::get('/produtos/{produto}/lotes', [ProdutoController::class, 'lotes'])->name('produtos.lotes');
        Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
        Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
        Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');
        
        // Operações de Lote disparadas por dentro do módulo de produtos
        Route::post('/lotes', [LoteController::class, 'store'])->name('lotes.store');
        Route::patch('/lotes/{lote}/desativar', [LoteController::class, 'desativar'])->name('lotes.desativar');
        Route::patch('/lotes/{lote}/ativar', [LoteController::class, 'ativar'])->name('lotes.ativar');
    });

    // Receitas e Módulo do Laboratório (Pesagem de Insumos)
    Route::middleware('perfil:receitas')->group(function () {
        Route::get('/receitas', [ReceitaController::class, 'index'])->name('receitas.index');
        Route::get('/receitas/{receita}', [ReceitaController::class, 'show'])->name('receitas.show');
        Route::post('/receitas', [ReceitaController::class, 'store'])->name('receitas.store');
        Route::put('/receitas/{receita}', [ReceitaController::class, 'update'])->name('receitas.update');
        Route::delete('/receitas/{receita}', [ReceitaController::class, 'destroy'])->name('receitas.destroy');
        
        // Fluxo de Pesagem na Balança
        Route::get('/receitas/{receita}/pesagem', [ReceitaController::class, 'pesagem'])->name('receitas.pesagem');
        Route::post('/receitas/{receita}/pesagem', [ReceitaController::class, 'confirmarPesagem'])->name('receitas.pesagem.confirmar');
    });

    // Financeiro e Frente de Caixa (Vendas e Emissão de Recibo)
    Route::middleware('perfil:financeiro')->group(function () {
        Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');
        Route::post('/financeiro/transacao', [FinanceiroController::class, 'storeTransacao'])->name('financeiro.transacao.store');
        Route::delete('/financeiro/transacao/{transacao}', [FinanceiroController::class, 'destroyTransacao'])->name('financeiro.transacao.destroy');
        Route::get('/financeiro/cliente/{cliente}', [FinanceiroController::class, 'historicoCliente'])->name('financeiro.historico');
        
        // Fluxo de Faturamento por CPF
        Route::post('/financeiro/venda/buscar', [FinanceiroController::class, 'buscarVenda'])->name('financeiro.venda.buscar');
        Route::post('/financeiro/venda/confirmar', [FinanceiroController::class, 'confirmarVenda'])->name('financeiro.venda.confirmar');
        Route::get('/financeiro/nota/{transacao}', [FinanceiroController::class, 'notaFiscal'])->name('financeiro.nota');
    });
});