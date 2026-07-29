<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->query('q');

        $usuarios = User::query()
            ->when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('cpf', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->get();

        return view('usuarios.index', compact('usuarios', 'busca'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        User::create($dados);

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário cadastrado com sucesso.');
    }

    public function update(Request $request, User $usuario)
    {
        $dados = $this->validar($request, $usuario->id);

        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        $usuario->update($dados);

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('sucesso', 'Usuário excluído com sucesso.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        // Na edição, se a senha vier vazia, remove do request antes de validar
        // assim o Laravel não exige nem valida o campo
        if ($id && !$request->filled('password')) {
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
        }

        $regraSenha = $id ? ['nullable'] : ['required'];

        $dados = $request->validate(
            [
                'nome'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
                'cpf'      => ['required', 'string', 'max:14', Rule::unique('users', 'cpf')->ignore($id)],
                'perfil'   => ['required', Rule::in(['admin', 'farmaceutico', 'balconista'])],
                'crf'      => ['nullable', 'required_if:perfil,farmaceutico', 'string', 'max:50'],
                'password' => array_merge($regraSenha, ['nullable', 'confirmed', 'min:6']),
            ],
            [
                'nome.required'      => 'O campo Nome é obrigatório.',
                'email.required'     => 'O campo E-mail é obrigatório.',
                'email.email'        => 'Informe um e-mail válido.',
                'email.unique'       => 'Este e-mail já está cadastrado no sistema.',
                'cpf.required'       => 'O campo CPF é obrigatório.',
                'cpf.unique'         => 'Este CPF já está cadastrado no sistema.',
                'perfil.required'    => 'Selecione um perfil para o usuário.',
                'perfil.in'          => 'O perfil selecionado é inválido.',
                'crf.required_if'    => 'O campo CRF é obrigatório para o perfil Farmacêutico.',
                'password.required'  => 'O campo Senha é obrigatório.',
                'password.confirmed' => 'A confirmação de senha não confere.',
                'password.min'       => 'A senha deve ter no mínimo 6 caracteres.',
            ]
        );

        $dados['status'] = 'ativo';

        if ($dados['perfil'] !== 'farmaceutico') {
            $dados['crf'] = null;
        }

        return $dados;
    }
}