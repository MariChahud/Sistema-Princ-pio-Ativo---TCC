@extends('layouts.app')

@section('titulo', 'Usuários')

@section('conteudo')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gerenciar Usuários</h3>
      <button class="btn btn-primary" data-usuario-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Usuário
      </button>
    </div>

    <div class="card-content">
      <div class="toolbar">
        <form method="GET" action="{{ route('usuarios.index') }}" class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ $busca }}" placeholder="Buscar usuários..." onchange="this.form.submit()">
        </form>
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr><th>Nome</th><th>E-mail</th><th>CPF</th><th>Perfil</th><th>CRF</th><th>Ações</th></tr>
          </thead>
          <tbody>
            @forelse ($usuarios as $usuario)
              <tr>
                <td><strong>{{ $usuario->nome }}</strong></td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->cpf ?? '—' }}</td>
                <td><span class="badge badge-primary">{{ $usuario->perfilLabel() }}</span></td>
                <td>{{ $usuario->perfil === 'farmaceutico' && $usuario->crf ? $usuario->crf : '—' }}</td>
                <td class="table-actions">
                  <button class="btn btn-sm btn-outline"
                    data-usuario-editar
                    data-id="{{ $usuario->id }}"
                    data-nome="{{ $usuario->nome }}"
                    data-email="{{ $usuario->email }}"
                    data-cpf="{{ $usuario->cpf }}"
                    data-perfil="{{ $usuario->perfil }}"
                    data-crf="{{ $usuario->crf ?? '' }}">Editar</button>

                  <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                    data-confirm="Tem certeza que deseja excluir este usuário?" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="empty-state">Nenhum usuário encontrado</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('modais')
  {{-- O modal reabre automaticamente se o Laravel retornar com erros de validação --}}
  <div class="modal-overlay {{ $errors->any() ? 'active' : '' }}" id="usuarioModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title" id="usuarioModalTitle">Novo Usuário</h3>
        <button class="modal-close" data-close-modal="usuarioModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="usuarioForm" method="POST" action="{{ route('usuarios.store') }}" novalidate>
        @csrf
        {{-- _method e _usuario_id são preenchidos pelo JS ao abrir o modal --}}
        <input type="hidden" name="_method"      id="usuarioMethod" value="POST">
        <input type="hidden" name="_usuario_id"  id="usuarioId"     value="">

        <div class="modal-body">

          <div class="form-group">
            <label for="usuarioNome">Nome Completo</label>
            <input type="text" id="usuarioNome" name="nome"
              value="{{ old('nome') }}"
              class="{{ $errors->has('nome') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('nome') ? 'visivel' : '' }}" id="erroNome">
              {{ $errors->first('nome') ?: 'O campo Nome é obrigatório.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="usuarioEmail">E-mail</label>
            <input type="email" id="usuarioEmail" name="email"
              value="{{ old('email') }}"
              class="{{ $errors->has('email') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('email') ? 'visivel' : '' }}" id="erroEmail">
              {{ $errors->first('email') ?: 'Informe um e-mail válido.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="usuarioCPF">CPF</label>
            <input type="text" id="usuarioCPF" name="cpf"
              value="{{ old('cpf') }}"
              placeholder="000.000.000-00" data-mask="cpf" maxlength="14"
              class="{{ $errors->has('cpf') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('cpf') ? 'visivel' : '' }}" id="erroCPF">
              {{ $errors->first('cpf') ?: 'O campo CPF é obrigatório.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="usuarioPerfil">Perfil</label>
            <select id="usuarioPerfil" name="perfil"
              class="{{ $errors->has('perfil') ? 'input-erro' : '' }}">
              <option value="">Selecione...</option>
              <option value="admin"        {{ old('perfil') === 'admin'        ? 'selected' : '' }}>Administrador</option>
              <option value="farmaceutico" {{ old('perfil') === 'farmaceutico' ? 'selected' : '' }}>Farmacêutico</option>
              <option value="balconista"   {{ old('perfil') === 'balconista'   ? 'selected' : '' }}>Balconista</option>
            </select>
            <span class="field-error {{ $errors->has('perfil') ? 'visivel' : '' }}" id="erroPerfil">
              {{ $errors->first('perfil') ?: 'Selecione um perfil.' }}
            </span>
          </div>

          <div class="form-group" id="crfField"
            style="{{ old('perfil') === 'farmaceutico' || $errors->has('crf') ? 'display:block' : 'display:none' }}">
            <label for="usuarioCRF">CRF</label>
            <input type="text" id="usuarioCRF" name="crf"
              value="{{ old('crf') }}"
              placeholder="CRF-SP 00000"
              class="{{ $errors->has('crf') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('crf') ? 'visivel' : '' }}" id="erroCRF">
              {{ $errors->first('crf') ?: 'O campo CRF é obrigatório para Farmacêutico.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="usuarioSenha">
              Senha
              <span id="senhaHint" style="font-size:0.75rem;color:var(--muted);font-weight:400;">
                (deixe em branco para manter a atual)
              </span>
            </label>
            <input type="password" id="usuarioSenha" name="password" placeholder="••••••••"
              class="{{ $errors->has('password') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('password') ? 'visivel' : '' }}" id="erroSenha">
              {{ $errors->first('password') ?: 'A senha deve ter no mínimo 6 caracteres.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="usuarioSenhaConf">Confirmar Senha</label>
            <input type="password" id="usuarioSenhaConf" name="password_confirmation" placeholder="••••••••">
            <span class="field-error" id="erroSenhaConf">A confirmação de senha não confere.</span>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="usuarioModal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  const usuarioForm  = document.getElementById('usuarioForm');
  const usuarioBase  = "{{ url('usuarios') }}";
  const perfilSelect = document.getElementById('usuarioPerfil');
  const crfField     = document.getElementById('crfField');
  const crfInput     = document.getElementById('usuarioCRF');
  const senhaHint    = document.getElementById('senhaHint');

  // Detecta se o modal está aberto por erro do Laravel e se era edição
  const temErros      = {{ $errors->any() ? 'true' : 'false' }};
  const erroEhEdicao  = {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }};
  const erroUsuarioId = "{{ old('_usuario_id') }}";
  let   modoEdicao    = false;

  // Se voltou com erro de edição, restaura a action e o modo
  if (temErros && erroEhEdicao && erroUsuarioId) {
    modoEdicao = true;
    usuarioForm.action = usuarioBase + '/' + erroUsuarioId;
    document.getElementById('usuarioMethod').value = 'PUT';
    document.getElementById('usuarioId').value     = erroUsuarioId;
    document.getElementById('usuarioModalTitle').textContent = 'Editar Usuário';
    senhaHint.style.display = 'inline';
  }

  // ── CRF ────────────────────────────────────────────────────────────
  function atualizarCRF() {
    const ehFarma = perfilSelect.value === 'farmaceutico';
    crfField.style.display = ehFarma ? 'block' : 'none';
  }
  perfilSelect.addEventListener('change', atualizarCRF);

  // mostrarErro() e limparErro() agora são globais, definidas em public/js/app.js
  function limparTodos() {
    [['usuarioNome','erroNome'],['usuarioEmail','erroEmail'],['usuarioCPF','erroCPF'],
     ['usuarioPerfil','erroPerfil'],['usuarioCRF','erroCRF'],
     ['usuarioSenha','erroSenha'],['usuarioSenhaConf','erroSenhaConf']]
    .forEach(([i,e]) => limparErro(i, e));
  }

  // Limpa erro ao digitar
  [['usuarioNome','erroNome'],['usuarioEmail','erroEmail'],['usuarioCPF','erroCPF'],
   ['usuarioPerfil','erroPerfil'],['usuarioCRF','erroCRF'],
   ['usuarioSenha','erroSenha'],['usuarioSenhaConf','erroSenhaConf']]
  .forEach(([inputId, erroId]) => {
    const el = document.getElementById(inputId);
    if (el) el.addEventListener('input', () => limparErro(inputId, erroId));
  });

  // ── Validação JS antes de enviar ───────────────────────────────────
  usuarioForm.addEventListener('submit', function(e) {
    limparTodos();
    let ok = true;

    if (!document.getElementById('usuarioNome').value.trim()) {
      mostrarErro('usuarioNome', 'erroNome', 'O campo Nome é obrigatório.'); ok = false;
    }

    const email = document.getElementById('usuarioEmail').value.trim();
    if (!email) {
      mostrarErro('usuarioEmail', 'erroEmail', 'O campo E-mail é obrigatório.'); ok = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      mostrarErro('usuarioEmail', 'erroEmail', 'Informe um e-mail válido.'); ok = false;
    }

    if (!document.getElementById('usuarioCPF').value.trim()) {
      mostrarErro('usuarioCPF', 'erroCPF', 'O campo CPF é obrigatório.'); ok = false;
    }

    if (!perfilSelect.value) {
      mostrarErro('usuarioPerfil', 'erroPerfil', 'Selecione um perfil para o usuário.'); ok = false;
    }

    if (perfilSelect.value === 'farmaceutico' && !crfInput.value.trim()) {
      mostrarErro('usuarioCRF', 'erroCRF', 'O campo CRF é obrigatório para Farmacêutico.'); ok = false;
    }

    const senha = document.getElementById('usuarioSenha').value;
    const conf  = document.getElementById('usuarioSenhaConf').value;

    // Senha obrigatória só no cadastro; na edição pode ficar em branco
    if (!modoEdicao && !senha) {
      mostrarErro('usuarioSenha', 'erroSenha', 'O campo Senha é obrigatório.'); ok = false;
    } else if (senha && senha.length < 6) {
      mostrarErro('usuarioSenha', 'erroSenha', 'A senha deve ter no mínimo 6 caracteres.'); ok = false;
    } else if (senha && senha !== conf) {
      mostrarErro('usuarioSenhaConf', 'erroSenhaConf', 'A confirmação de senha não confere.'); ok = false;
    }

    if (!ok) e.preventDefault();
  });

  // ── Abrir: Novo Usuário ────────────────────────────────────────────
  document.querySelectorAll('[data-usuario-novo]').forEach(btn => btn.addEventListener('click', () => {
    modoEdicao = false;
    usuarioForm.reset();
    limparTodos();
    usuarioForm.action = usuarioBase;
    document.getElementById('usuarioMethod').value = 'POST';
    document.getElementById('usuarioId').value     = '';
    document.getElementById('usuarioModalTitle').textContent = 'Novo Usuário';
    senhaHint.style.display = 'none';
    atualizarCRF();
    openModal('usuarioModal');
  }));

  // ── Abrir: Editar Usuário ──────────────────────────────────────────
  document.querySelectorAll('[data-usuario-editar]').forEach(btn => btn.addEventListener('click', () => {
    modoEdicao = true;
    usuarioForm.reset();
    limparTodos();
    usuarioForm.action = usuarioBase + '/' + btn.dataset.id;
    document.getElementById('usuarioMethod').value = 'PUT';
    document.getElementById('usuarioId').value     = btn.dataset.id;
    document.getElementById('usuarioModalTitle').textContent = 'Editar Usuário';
    document.getElementById('usuarioNome').value   = btn.dataset.nome;
    document.getElementById('usuarioEmail').value  = btn.dataset.email;
    document.getElementById('usuarioCPF').value    = btn.dataset.cpf || '';
    perfilSelect.value = btn.dataset.perfil;
    crfInput.value     = btn.dataset.crf || '';
    senhaHint.style.display = 'inline';
    atualizarCRF();
    openModal('usuarioModal');
  }));
</script>
@endpush