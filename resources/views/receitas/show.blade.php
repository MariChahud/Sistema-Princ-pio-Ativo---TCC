@extends('layouts.app')

@section('titulo', 'Detalhes da Receita')

@section('conteudo')
  <a href="{{ route('receitas.index') }}" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Voltar para Receitas
  </a>

  <div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3 class="card-title">Ordem de Manipulação #{{ str_pad($receita->id, 3, '0', STR_PAD_LEFT) }}</h3>
      <span class="badge {{ $receita->statusBadgeClass() }}">{{ $receita->statusLabel() }}</span>
    </div>
    <div class="card-content">
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1.5rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border);">
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Fórmula</span>
          <strong style="font-size:1.1rem;color:var(--text);">{{ $receita->nome_formula }}</strong>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Cliente</span>
          <strong style="display:block;">{{ $receita->cliente->nome ?? 'N/A' }}</strong>
          <span style="font-size:0.85rem;color:var(--muted);">CPF: {{ $receita->cliente->cpf ?? '—' }}</span>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Prescritor</span>
          <strong style="display:block;">{{ $receita->medico }}</strong>
          <span style="font-size:0.85rem;color:var(--muted);">CRM: {{ $receita->crm }}</span>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Data e Cápsulas</span>
          <strong style="display:block;">{{ $receita->data->format('d/m/Y') }}</strong>
          <span style="font-size:0.85rem;color:var(--muted);">{{ $receita->qtd_capsulas }} Cápsulas</span>
        </div>
      </div>

      <h4 style="margin-bottom: 1rem;color:var(--primary-dark);">Composição / Insumos Separados</h4>
      <div class="table-container">
        <table>
          <thead>
            <tr><th>Insumo (Matéria-Prima)</th><th>Lote Utilizado</th><th>Dosagem p/ Cápsula</th><th>Quantidade Total Requerida</th></tr>
          </thead>
          <tbody>
            @foreach ($receita->itens as $item)
              <tr>
                <td><strong>{{ $item->produto->nome ?? 'N/A' }}</strong></td>
                <td><span class="badge badge-secondary">{{ $item->lote->numero ?? 'N/A' }}</span></td>
                <td>{{ number_format($item->dosagem_mg, 2, ',', '.') }} mg</td>
                <td>
                  <strong>{{ number_format(($item->dosagem_mg * $receita->qtd_capsulas) / 1000, 3, ',', '.') }} g</strong>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:space-between;align-items:center;background:var(--background-light);padding:1rem;border-radius:var(--radius);">
        <div>
          <span style="font-size:0.85rem;color:var(--muted);">Orçamento Final da Fórmula:</span>
          <strong style="font-size:1.25rem;color:#16a34a;margin-left:8px;">R$ {{ number_format($receita->orcamento, 2, ',', '.') }}</strong>
        </div>

        <div style="display:flex;gap:0.5rem;">
          @if ($receita->status === 'aguardando_pesagem')
            <a href="{{ route('receitas.pesagem', $receita) }}" class="btn btn-primary">Iniciar Processo de Pesagem</a>
          @endif
          @if ($receita->status !== 'aguardando_pesagem')
            <button onclick="window.print()" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
              Imprimir Ficha
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection