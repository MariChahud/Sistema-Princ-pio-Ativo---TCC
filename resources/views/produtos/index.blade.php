@extends('layouts.app')

@section('titulo', 'Produtos e Estoque')

@section('conteudo')
  {{-- Menu de Abas Alternáveis --}}
  <div class="tabs">
    <button class="tab active" data-tab="produtosTab">Produtos</button>
    <button class="tab" data-tab="lotesTab">Lotes</button>
  </div>

  {{-- ABA 1: PRODUTOS / INSUMOS --}}
  <div class="tab-content" id="produtosTab">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Gerenciar Produtos</h3>
        <button class="btn btn-primary" data-produto-novo>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Novo Produto
        </button>
      </div>
      <div class="card-content">
        <div class="toolbar">
          <form method="GET" action="{{ route('produtos.index') }}" class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="{{ $busca }}" placeholder="Buscar produtos..." onchange="this.form.submit()">
          </form>
        </div>
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Nome</th><th>DCB</th><th>Preço Base</th><th>Estoque</th><th>Mínimo</th><th>Ações</th></tr>
            </thead>
            <tbody>
              @forelse ($produtos as $produto)
                @php $alerta = $produto->lotesValidadeProxima()->count() > 0; @endphp
                <tr>
                  <td>
                    <a href="{{ route('produtos.lotes', $produto) }}" class="produto-nome-link" style="font-weight:700;color:var(--primary);text-decoration:none;">{{ $produto->nome }}</a>
                    @if ($alerta)
                      <span title="Lote com validade próxima" style="color:#f59e0b;margin-left:6px;vertical-align:middle;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                      </span>
                    @endif
                  </td>
                  <td>{{ $produto->dcb }}</td>
                  <td>R$ {{ number_format($produto->preco_base, 2, ',', '.') }}/{{ $produto->unidade }}</td>
                  <td>
                    {{-- Badge muda de cor dinamicamente se o estoque estiver crítico --}}
                    <span class="badge {{ $produto->abaixoDoMinimo() ? 'badge-danger' : 'badge-success' }}">
                      {{ rtrim(rtrim(number_format($produto->estoque_atual, 4, '.', ''), '0'), '.') }} {{ $produto->unidade }}
                    </span>
                  </td>
                  <td>{{ $produto->estoque_minimo }} {{ $produto->unidade }}</td>
                  <td class="table-actions">
                    <button class="btn btn-sm btn-outline"
                      data-produto-editar
                      data-id="{{ $produto->id }}"
                      data-nome="{{ $produto->nome }}"
                      data-dcb="{{ $produto->dcb }}"
                      data-unidade="{{ $produto->unidade }}"
                      data-preco="{{ $produto->preco_base }}"
                      data-min="{{ $produto->estoque_minimo }}">Editar</button>
                    <form method="POST" action="{{ route('produtos.destroy', $produto) }}" data-confirm="Tem certeza que deseja excluir este produto?" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="empty-state">Nenhum produto encontrado</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- ABA 2: LOTES (visão geral, todos os produtos) --}}
  <div class="tab-content" id="lotesTab" style="display:none;">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Gerenciar Lotes</h3>
        <button class="btn btn-primary" data-lote-novo>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Novo Lote
        </button>
      </div>
      <div class="card-content">
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Número</th><th>Produto</th><th>Quantidade</th><th>Validade</th><th>Fornecedor</th><th>Status</th><th>Ações</th></tr>
            </thead>
            <tbody>
              @forelse ($lotes as $lote)
                @php
                  $dias = $lote->diasParaVencer();
                  $vc = $dias <= 30 ? 'badge-danger' : ($dias <= 90 ? 'badge-warning' : 'badge-success');
                @endphp
                <tr style="{{ !$lote->ativo ? 'opacity:0.5;' : '' }}">
                  <td>{{ $lote->numero }}</td>
                  <td>{{ $lote->produto->nome ?? 'N/A' }}</td>
                  <td>{{ rtrim(rtrim(number_format($lote->quantidade, 4, '.', ''), '0'), '.') }}</td>
                  <td><span class="badge {{ $vc }}">{{ $lote->validade->format('d/m/Y') }}</span></td>
                  <td>{{ $lote->fornecedor }}</td>
                  <td>
                    @if ($lote->ativo)
                      <span class="badge badge-success">Ativo</span>
                    @else
                      <span class="badge badge-secondary">Desativado</span>
                    @endif
                  </td>
                  <td class="table-actions">
                    @if ($lote->ativo)
                      <form method="POST" action="{{ route('lotes.desativar', $lote) }}" data-confirm="Desativar este lote? Ele não aparecerá ao registrar receitas." style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger">Desativar</button>
                      </form>
                    @else
                      <form method="POST" action="{{ route('lotes.ativar', $lote) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline">Reativar</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="empty-state">Nenhum lote cadastrado</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modais')
  @include('produtos.modais')
@endsection

@push('scripts')
<script>
  // mudar para abas de lotes e produtos
  document.querySelectorAll('[data-tab]').forEach(tabBtn => {
    tabBtn.addEventListener('click', () => {
      document.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
      tabBtn.classList.add('active');
      const target = document.getElementById(tabBtn.dataset.tab);
      if (target) target.style.display = 'block';
    });
  });

  // modal produto
  const produtoForm = document.getElementById('produtoForm');
  const produtoBase = "{{ url('produtos') }}";

  document.querySelectorAll('[data-produto-novo]').forEach(b => b.addEventListener('click', () => {
    produtoForm.reset();
    produtoForm.action = produtoBase;
    document.getElementById('produtoMethod').value = 'POST';
    document.getElementById('produtoModalTitle').textContent = 'Novo Produto';
    openModal('produtoModal');
  }));

  document.querySelectorAll('[data-produto-editar]').forEach(b => b.addEventListener('click', () => {
    produtoForm.reset();
    produtoForm.action = produtoBase + '/' + b.dataset.id;
    document.getElementById('produtoMethod').value = 'PUT';
    document.getElementById('produtoId').value = b.dataset.id;
    document.getElementById('produtoModalTitle').textContent = 'Editar Produto';
    document.getElementById('produtoNome').value = b.dataset.nome;
    document.getElementById('produtoDCB').value = b.dataset.dcb;
    document.getElementById('produtoUnidade').value = b.dataset.unidade;
    document.getElementById('produtoPreco').value = b.dataset.preco;
    document.getElementById('produtoEstoqueMin').value = b.dataset.min;
    openModal('produtoModal');
  }));

  document.querySelectorAll('[data-produto-novo]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('produtoId').value = '';
  }));

  // modal lote
  document.querySelectorAll('[data-lote-novo]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('loteForm').reset();
    openModal('loteModal');
  }));
</script>
@endpush