@extends('layouts.app')

@section('titulo', 'Clientes')

@section('conteudo')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gerenciar Clientes</h3>
      <button class="btn btn-primary" data-cliente-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Cliente
      </button>
    </div>

    <div class="card-content">
      <div class="toolbar">
        <form method="GET" action="{{ route('clientes.index') }}" class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ $busca }}" placeholder="Buscar clientes..." onchange="this.form.submit()">
        </form>
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>CPF</th><th>Ações</th></tr>
          </thead>
          <tbody>
            @forelse ($clientes as $cliente)
              <tr>
                <td><strong>{{ $cliente->nome }}</strong></td>
                <td>{{ $cliente->email }}</td>
                <td>{{ $cliente->telefone }}</td>
                <td>{{ $cliente->cpf }}</td>
                <td class="table-actions">
                  <button class="btn btn-sm btn-outline"
                    data-cliente-editar
                    data-id="{{ $cliente->id }}"
                    data-nome="{{ $cliente->nome }}"
                    data-email="{{ $cliente->email }}"
                    data-telefone="{{ $cliente->telefone }}"
                    data-cpf="{{ $cliente->cpf }}">Editar</button>

                  <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                    data-confirm="Tem certeza que deseja excluir este cliente?" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="empty-state">Nenhum cliente encontrado</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('modais')
  <div class="modal-overlay {{ $errors->any() ? 'active' : '' }}" id="clienteModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title" id="clienteModalTitle">Novo Cliente</h3>
        <button class="modal-close" data-close-modal="clienteModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="clienteForm" method="POST" action="{{ route('clientes.store') }}" novalidate>
        @csrf
        <input type="hidden" name="_method"     id="clienteMethod" value="POST">
        <input type="hidden" name="_cliente_id" id="clienteId"     value="">

        <div class="modal-body">

          <div class="form-group">
            <label for="clienteNome">Nome Completo</label>
            <input type="text" id="clienteNome" name="nome"
              value="{{ old('nome') }}"
              class="{{ $errors->has('nome') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('nome') ? 'visivel' : '' }}" id="erroNome">
              {{ $errors->first('nome') ?: 'O campo Nome é obrigatório.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="clienteEmail">E-mail</label>
            <input type="email" id="clienteEmail" name="email"
              value="{{ old('email') }}"
              class="{{ $errors->has('email') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('email') ? 'visivel' : '' }}" id="erroEmail">
              {{ $errors->first('email') ?: 'Informe um e-mail válido.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="clienteTelefone">Telefone</label>
            <input type="tel" id="clienteTelefone" name="telefone"
              value="{{ old('telefone') }}"
              placeholder="(00) 00000-0000"
              class="{{ $errors->has('telefone') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('telefone') ? 'visivel' : '' }}" id="erroTelefone">
              {{ $errors->first('telefone') ?: 'O campo Telefone é obrigatório.' }}
            </span>
          </div>

          <div class="form-group">
            <label for="clienteCPF">CPF</label>
            <input type="text" id="clienteCPF" name="cpf"
              value="{{ old('cpf') }}"
              placeholder="000.000.000-00" data-mask="cpf" maxlength="14"
              class="{{ $errors->has('cpf') ? 'input-erro' : '' }}">
            <span class="field-error {{ $errors->has('cpf') ? 'visivel' : '' }}" id="erroCPF">
              {{ $errors->first('cpf') ?: 'O campo CPF é obrigatório.' }}
            </span>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="clienteModal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  const clienteForm   = document.getElementById('clienteForm');
  const clienteBase   = "{{ url('clientes') }}";
  const temErros      = {{ $errors->any() ? 'true' : 'false' }};
  const erroEhEdicao  = {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }};
  const erroClienteId = "{{ old('_cliente_id') }}";

  // Restaura modo edição se o Laravel retornou com erro de validação
  if (temErros && erroEhEdicao && erroClienteId) {
    clienteForm.action = clienteBase + '/' + erroClienteId;
    document.getElementById('clienteMethod').value = 'PUT';
    document.getElementById('clienteModalTitle').textContent = 'Editar Cliente';
  }

  // mostrarErro() e limparErro() agora são globais, definidas em public/js/app.js
  function limparTodos() {
    [['clienteNome','erroNome'],['clienteEmail','erroEmail'],
     ['clienteTelefone','erroTelefone'],['clienteCPF','erroCPF']]
    .forEach(([i, e]) => limparErro(i, e));
  }

  // Limpa erro ao digitar
  [['clienteNome','erroNome'],['clienteEmail','erroEmail'],
   ['clienteTelefone','erroTelefone'],['clienteCPF','erroCPF']]
  .forEach(([inputId, erroId]) => {
    const el = document.getElementById(inputId);
    if (el) el.addEventListener('input', () => limparErro(inputId, erroId));
  });

  // ── Validação JS ─────────────────────────────────────────────────
  clienteForm.addEventListener('submit', function(e) {
    limparTodos();
    let ok = true;

    if (!document.getElementById('clienteNome').value.trim()) {
      mostrarErro('clienteNome', 'erroNome', 'O campo Nome é obrigatório.'); ok = false;
    }

    const email = document.getElementById('clienteEmail').value.trim();
    if (!email) {
      mostrarErro('clienteEmail', 'erroEmail', 'O campo E-mail é obrigatório.'); ok = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      mostrarErro('clienteEmail', 'erroEmail', 'Informe um e-mail válido.'); ok = false;
    }

    if (!document.getElementById('clienteTelefone').value.trim()) {
      mostrarErro('clienteTelefone', 'erroTelefone', 'O campo Telefone é obrigatório.'); ok = false;
    }

    if (!document.getElementById('clienteCPF').value.trim()) {
      mostrarErro('clienteCPF', 'erroCPF', 'O campo CPF é obrigatório.'); ok = false;
    }

    if (!ok) e.preventDefault();
  });

  // ── Abrir: Novo Cliente ──────────────────────────────────────────
  document.querySelectorAll('[data-cliente-novo]').forEach(btn => btn.addEventListener('click', () => {
    clienteForm.reset();
    limparTodos();
    clienteForm.action = clienteBase;
    document.getElementById('clienteMethod').value = 'POST';
    document.getElementById('clienteId').value     = '';
    document.getElementById('clienteModalTitle').textContent = 'Novo Cliente';
    openModal('clienteModal');
  }));

  // ── Abrir: Editar Cliente ────────────────────────────────────────
  document.querySelectorAll('[data-cliente-editar]').forEach(btn => btn.addEventListener('click', () => {
    clienteForm.reset();
    limparTodos();
    clienteForm.action = clienteBase + '/' + btn.dataset.id;
    document.getElementById('clienteMethod').value = 'PUT';
    document.getElementById('clienteId').value     = btn.dataset.id;
    document.getElementById('clienteModalTitle').textContent = 'Editar Cliente';
    document.getElementById('clienteNome').value     = btn.dataset.nome;
    document.getElementById('clienteEmail').value    = btn.dataset.email;
    document.getElementById('clienteTelefone').value = btn.dataset.telefone;
    document.getElementById('clienteCPF').value      = btn.dataset.cpf;
    openModal('clienteModal');
  }));
</script>
@endpush