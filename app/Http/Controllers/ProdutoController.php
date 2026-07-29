<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->query('q');

        $produtos = Produto::query()
            ->when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('dcb', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->get();

        $lotes = \App\Models\Lote::with('produto')->orderByDesc('id')->get();

        return view('produtos.index', compact('produtos', 'lotes', 'busca'));
    }

    public function lotes(Produto $produto)
    {
        $produto->load('lotes');
        return view('produtos.lotes', compact('produto'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        $dados['estoque_atual'] = 0;

        Produto::create($dados);

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto cadastrado com sucesso.');
    }

    public function update(Request $request, Produto $produto)
    {
        $dados = $this->validar($request, $produto->id);
        $produto->update($dados);

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto atualizado com sucesso.');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto excluído com sucesso.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        return $request->validate(
            [
                'nome'           => ['required', 'string', 'max:255', Rule::unique('produtos', 'nome')->ignore($id)],
                'dcb'            => ['required', 'string', 'max:50',  Rule::unique('produtos', 'dcb')->ignore($id)],
                'unidade'        => ['required', Rule::in(['g'])],
                'preco_base'     => ['required', 'numeric', 'min:0'],
                'estoque_minimo' => ['required', 'integer', 'min:0'],
            ],
            [
                'nome.required'           => 'O campo Nome é obrigatório.',
                'nome.unique'             => 'Já existe um produto com este nome no sistema.',
                'dcb.required'            => 'O campo DCB é obrigatório.',
                'dcb.unique'              => 'Este DCB já está cadastrado no sistema.',
                'unidade.required'        => 'A unidade de medida é obrigatória.',
                'unidade.in'              => 'A unidade de medida deve ser gramas (g).',
                'preco_base.required'     => 'O campo Preço Base é obrigatório.',
                'preco_base.numeric'      => 'O Preço Base deve ser um valor numérico.',
                'preco_base.min'          => 'O Preço Base não pode ser negativo.',
                'estoque_minimo.required' => 'O campo Estoque Mínimo é obrigatório.',
                'estoque_minimo.integer'  => 'O Estoque Mínimo deve ser um número inteiro.',
                'estoque_minimo.min'      => 'O Estoque Mínimo não pode ser negativo.',
            ]
        );
    }
}