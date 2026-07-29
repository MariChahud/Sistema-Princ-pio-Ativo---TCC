<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Transacao;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes     = Cliente::count();
        $totalProdutos     = Produto::count();
        $receitasPendentes = Receita::where('status', 'aguardando_pesagem')->count();

        $entradas = Transacao::where('tipo', 'entrada')->sum('valor');
        $saidas   = Transacao::where('tipo', 'saida')->sum('valor');
        $saldoMes = $entradas - $saidas;

        $alertas = [];

        foreach (Produto::all() as $produto) {
            if ($produto->abaixoDoMinimo()) {
                $alertas[] = [
                    'tipo'     => 'warning',
                    'titulo'   => 'Estoque Baixo',
                    'mensagem' => "{$produto->nome} está abaixo do mínimo ({$produto->estoque_atual}/{$produto->estoque_minimo})",
                ];
            }
        }

        $lotesProximos = Lote::with('produto')
            ->where('ativo', true)
            ->whereDate('validade', '>', now())
            ->whereDate('validade', '<=', now()->addDays(30))
            ->get();

        foreach ($lotesProximos as $lote) {
            $dias = $lote->diasParaVencer();
            $alertas[] = [
                'tipo'     => 'danger',
                'titulo'   => 'Validade Próxima',
                'mensagem' => "Lote {$lote->numero} de " . ($lote->produto->nome ?? 'Produto') . " vence em {$dias} dias",
            ];
        }


        return view('dashboard', compact(
            'totalClientes',
            'totalProdutos',
            'receitasPendentes',
            'saldoMes',
            'alertas'
        ));
    }
}