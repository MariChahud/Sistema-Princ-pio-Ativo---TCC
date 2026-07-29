<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->query('q');

        $clientes = Cliente::query()
            ->when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('cpf', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->get();

        return view('clientes.index', compact('clientes', 'busca'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        Cliente::create($dados);

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente cadastrado com sucesso.');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $dados = $this->validar($request, $cliente->id);
        $cliente->update($dados);

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente excluído com sucesso.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        return $request->validate(
            [
                'nome'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', Rule::unique('clientes', 'email')->ignore($id)],
                'telefone' => ['required', 'string', 'max:20'],
                'cpf'      => ['required', 'string', 'max:14', Rule::unique('clientes', 'cpf')->ignore($id)],
            ],
            [
                'nome.required'     => 'O campo Nome é obrigatório.',
                'email.required'    => 'O campo E-mail é obrigatório.',
                'email.email'       => 'Informe um e-mail válido.',
                'email.unique'      => 'Este e-mail já está cadastrado no sistema.',
                'telefone.required' => 'O campo Telefone é obrigatório.',
                'cpf.required'      => 'O campo CPF é obrigatório.',
                'cpf.unique'        => 'Este CPF já está cadastrado no sistema.',
            ]
        );
    }
}