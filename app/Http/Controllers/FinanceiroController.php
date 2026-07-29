<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Receita;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->query('tipo', 'todos');

        $transacoes = Transacao::query()
            ->when($filtro !== 'todos', fn ($q) => $q->where('tipo', $filtro))
            ->orderByDesc('data')
            ->get();

        $entradas = Transacao::where('tipo', 'entrada')->sum('valor');
        $saidas   = Transacao::where('tipo', 'saida')->sum('valor');

        // lista para a aba "Histórico de Clientes".
        $clientes = Cliente::withCount('receitas')->orderBy('nome')->get();

        // Se um cliente foi buscado para venda, carrega suas receitas "pesadas"
        $clienteVenda      = null;
        $receitasDisponiveis = collect();

        if ($request->filled('cliente_venda')) {
            $clienteVenda = Cliente::find($request->query('cliente_venda'));

            if ($clienteVenda) {
                $receitasDisponiveis = $clienteVenda->receitas()
                    ->with(['itens.produto', 'itens.lote'])
                    ->where('status', 'pesado')
                    ->orderByDesc('data')
                    ->get();
            }
        }

        return view('financeiro.index', [
            'transacoes'          => $transacoes,
            'totalEntradas'       => $entradas,
            'totalSaidas'         => $saidas,
            'saldoAtual'          => $entradas - $saidas,
            'clientes'            => $clientes,
            'filtro'              => $filtro,
            'clienteVenda'        => $clienteVenda,
            'receitasDisponiveis' => $receitasDisponiveis,
        ]);
    }

    public function storeTransacao(Request $request)
    {
        $dados = $request->validate([
            'tipo'            => ['required', 'in:entrada,saida'],
            'descricao'       => ['required', 'string', 'max:255'],
            'valor'           => ['required', 'numeric', 'min:0'],
            'data'            => ['required', 'date'],
            'categoria'       => ['required', 'in:vendas,fornecedores,salarios,aluguel,utilidades,outros'],
            'forma_pagamento' => ['required', 'in:dinheiro,pix,cartao,boleto,transferencia'],
        ]);

        Transacao::create($dados);

        return back()->with('sucesso', 'Transação registrada.');
    }

    public function destroyTransacao(Transacao $transacao)
    {
        $transacao->delete();
        return back()->with('sucesso', 'Transação excluída.');
    }

    // historico do cliente
    public function historicoCliente(Cliente $cliente)
    {
        $cliente->load(['receitas' => fn ($q) => $q->orderByDesc('data')]);
        $transacoes = $cliente->transacoes()->orderByDesc('data')->get();
        $totalGasto = $transacoes->sum('valor');

        return view('financeiro.historico', compact('cliente', 'transacoes', 'totalGasto'));
    }

    // Busca TODAS as receitas "pesadas" de um cliente pelo CPF
    public function buscarVenda(Request $request)
    {
        $request->validate(['cpf' => ['required', 'string']]);

        // Remove pontos e traços do CPF digitado para garantir a comparação limpa
        $cpf = preg_replace('/\D/', '', $request->input('cpf'));

        $cliente = Cliente::all()->first(function ($c) use ($cpf) {
            return preg_replace('/\D/', '', $c->cpf) === $cpf;
        });

        if (! $cliente) {
            return back()->with('erro', 'Receita não encontrada — cliente não cadastrado com este CPF.')
                ->withInput();
        }

        
        $receitasProntas = $cliente->receitas()
            ->with(['itens.produto', 'itens.lote'])
            ->where('status', 'pesado')
            ->orderByDesc('data')
            ->get();

        if ($receitasProntas->isEmpty()) {
            return back()->with('erro', 'Receita não encontrada — nenhuma receita "Pesada" para este cliente.')
                ->withInput();
        }

        return redirect()->route('financeiro.index', ['cliente_venda' => $cliente->id]);
    }


    public function confirmarVenda(Request $request)
    {
        $dados = $request->validate([
            'receitas'        => ['required', 'array', 'min:1'],
            'receitas.*'      => ['required', 'exists:receitas,id'],
            'forma_pagamento' => ['required', 'in:dinheiro,pix,cartao,boleto,transferencia'],
        ], [
            'receitas.required' => 'Selecione ao menos uma receita para faturar.',
        ]);

        $transacoes = DB::transaction(function () use ($dados) {
            $criadas = collect();

            foreach ($dados['receitas'] as $receitaId) {
                $receita = Receita::findOrFail($receitaId);

                if ($receita->status !== 'pesado') {
                    continue;
                }

                $receita->update(['status' => 'finalizado']);

                $criadas->push(Transacao::create([
                    'tipo'            => 'entrada',
                    'descricao'       => "Venda — {$receita->nome_formula} (Receita #" . str_pad($receita->id, 3, '0', STR_PAD_LEFT) . ')',
                    'valor'           => $receita->orcamento,
                    'data'            => now()->toDateString(),
                    'categoria'       => 'vendas',
                    'forma_pagamento' => $dados['forma_pagamento'],
                    'cliente_id'      => $receita->cliente_id,
                    'receita_id'      => $receita->id,
                ]));
            }

            return $criadas;
        });

        if ($transacoes->isEmpty()) {
            return back()->with('erro', 'Nenhuma das receitas selecionadas estava disponível para venda.');
        }

        return redirect()->route('financeiro.nota', [
            'transacao' => $transacoes->first()->id,
            'ids'       => $transacoes->pluck('id')->implode(','),
        ]);
    }

    // nota fiscal - recibo da compra
    public function notaFiscal(Request $request, Transacao $transacao)
    {
        $ids = collect(explode(',', (string) $request->query('ids', $transacao->id)))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values();

        $transacoes = Transacao::with(['cliente', 'receita.itens.produto', 'receita.itens.lote'])
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        if ($transacoes->isEmpty()) {
            $transacao->load(['cliente', 'receita.itens.produto', 'receita.itens.lote']);
            $transacoes = collect([$transacao]);
        }

        $cliente    = $transacoes->first()->cliente;
        $totalGeral = $transacoes->sum('valor');

        return view('financeiro.nota', compact('transacoes', 'cliente', 'totalGeral'));
    }
}