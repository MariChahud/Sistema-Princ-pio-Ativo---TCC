<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoteController extends Controller
{
    public function store(Request $request)
    {
        $dados = $request->validate(
            [
                'produto_id'      => ['required', 'exists:produtos,id'],
                'numero'          => ['required', 'string', 'max:50', Rule::unique('lotes', 'numero')],
                'quantidade'      => ['required', 'numeric', 'min:1'],
                'validade'        => ['required', 'date'],
                'fornecedor'      => ['required', 'string', 'max:255'],
                'cnpj_fornecedor' => ['required', 'string', 'max:18'],
            ],
            [
                'produto_id.required' => 'Selecione o produto correspondente ao lote.',
                'produto_id.exists'   => 'O produto selecionado não existe no sistema.',
                'numero.required'     => 'O campo Número do Lote é obrigatório.',
                'numero.unique'       => 'Este número de lote já está cadastrado no sistema.',
                'numero.max'          => 'O Número do Lote não pode ultrapassar 50 caracteres.',
                'quantidade.required' => 'O campo Quantidade é obrigatório.',
                'quantidade.numeric'  => 'A Quantidade deve ser um valor numérico.',
                'quantidade.min'      => 'A Quantidade deve ser maior que zero.',
                'validade.required'   => 'O campo Validade é obrigatório.',
                'validade.date'       => 'Informe uma data de validade válida.',
                'fornecedor.required' => 'O campo Fornecedor é obrigatório.',
                'fornecedor.max'      => 'O Fornecedor não pode ultrapassar 255 caracteres.',
                'cnpj_fornecedor.max' => 'O CNPJ informado é inválido.',
                'cnpj_fornecedor.required' => 'O campo CNPJ do Fornecedor é obrigatório.',
            ]
        );

        $dados['ativo'] = true;
        $lote = Lote::create($dados);

        $produto = $lote->produto;
        $produto->increment('estoque_atual', $lote->quantidade);

        return back()->with('sucesso', 'Lote cadastrado e estoque atualizado.');
    }

    public function desativar(Lote $lote)
    {
        if ($lote->ativo) {
            $lote->update(['ativo' => false]);

            $novoEstoque = max(0, $lote->produto->estoque_atual - $lote->quantidade);
            $lote->produto->update(['estoque_atual' => $novoEstoque]);
        }

        return back()->with('sucesso', 'Lote desativado.');
    }

    public function ativar(Lote $lote)
    {
        if (! $lote->ativo) {
            $lote->update(['ativo' => true]);
            $lote->produto->increment('estoque_atual', $lote->quantidade);
        }

        return back()->with('sucesso', 'Lote reativado.');
    }
}