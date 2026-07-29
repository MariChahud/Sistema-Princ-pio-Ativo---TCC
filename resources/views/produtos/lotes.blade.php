@extends('layouts.app')

@section('titulo', 'Produtos e Estoque')

@section('conteudo')
  <a href="{{ route('produtos.index') }}" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Voltar para Produtos
  </a>

  <div class="card">
    <div class="card-header">
      <div>
        <div style="display:flex;align-items:center;gap:12px;">
          <span style="font-size:1rem;font-weight:700;color:var(--primary-dark);">{{ $produto->nome }}</span>
          @if ($produto->abaixoDoMinimo())
            <span class="alerta-minimo" style="display:inline-flex;align-items:center;gap:5px;background:rgba(230,126,34,0.1);color:var(--warning);border:1px solid rgba(230,126,34,0.3);border-radius:9999px;padding:3px 10px;font-size:0.75rem;font-weight:700;">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              Alerta de Quantidade Mínima
            </span>
          @endif
        </div>
        <span style="font-size:0.75rem;color:var(--muted);">DCB: {{ $produto->dcb }} | Estoque atual: {{ rtrim(rtrim(number_format($produto->estoque_atual,4,'.',''),'0'),'.') }} {{ $produto->unidade }} | Mínimo: {{ $produto->estoque_minimo }} {{ $produto->unidade }}</span>
      </div>
      <button class="btn btn-primary" data-lote-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Lote
      </button>
    </div>

    <div class="card-content">
      <div class="table-container">
        <table>
          <thead>
            <tr><th>Número do Lote</th><th>Quantidade</th><th>Validade</th><th>Fornecedor</th><th>Status</th><th>Ações</th></tr>
          </thead>
          <tbody>
            @forelse ($produto->lotes as $lote)
              @php
                $dias = $lote->diasParaVencer();
                $vc = $dias <= 30 ? 'badge-danger' : ($dias <= 90 ? 'badge-warning' : 'badge-success');
              @endphp
              <tr style="{{ !$lote->ativo ? 'opacity:0.5;' : '' }}">
                <td><strong>{{ $lote->numero }}</strong></td>
                <td>{{ rtrim(rtrim(number_format($lote->quantidade,4,'.',''),'0'),'.') }}</td>
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
                    <form method="POST" action="{{ route('lotes.desativar', $lote) }}" data-confirm="Desativar este lote?" style="display:inline;">
                      @csrf @method('PATCH')
                      <button class="btn btn-sm btn-danger">Desativar</button>
                    </form>
                  @else
                    <form method="POST" action="{{ route('lotes.ativar', $lote) }}" style="display:inline;">
                      @csrf @method('PATCH')
                      <button class="btn btn-sm btn-outline">Reativar</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="empty-state">Sem lotes cadastrados para este produto</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('modais')
  @include('produtos.modais', ['produtoFixo' => $produto])
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-lote-novo]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('loteForm').reset();
    openModal('loteModal');
  }));
</script>
@endpush