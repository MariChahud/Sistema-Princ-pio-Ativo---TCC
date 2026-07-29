@extends('layouts.app')

@section('titulo', 'Extrato Financeiro do Cliente')

@section('conteudo')
  <a href="{{ route('financeiro.index', ['view_clientes' => 1]) }}" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Voltar para o Financeiro
  </a>

  <div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header" style="background: var(--background-light);">
      <div>
        <h3 class="card-title">Histórico de Movimentações</h3>
        <p style="margin:0; color:var(--muted); font-size:0.85rem;">Cliente: <strong>{{ $cliente->nome }}</strong> | CPF: {{ $cliente->cpf }}</p>
      </div>
      <div style="text-align: right;">
        <span style="font-size:0.75rem; color:var(--muted); display:block; font-weight:600;">VALOR TOTAL INVESTIDO:</span>
        <strong style="color: #10b981; font-size: 1.4rem;">R$ {{ number_format($totalGasto, 2, ',', '.') }}</strong>
      </div>
    </div>
    <div class="card-content">
      <h4 style="margin-bottom: 1rem; color: var(--primary-dark);">Vendas Faturadas / Pagamentos</h4>
      <div class="table-container">
        <table>
          <thead>
            <tr><th>Data</th><th>Nº Lançamento</th><th>Descrição / Fórmula</th><th>Forma de Pagamento</th><th>Valor Pago</th></tr>
          </thead>
          <tbody>
            @forelse($transacoes as $t)
              <tr>
                <td>{{ \Carbon\Carbon::parse($t->data)->format('d/m/Y') }}</td>
                <td>#{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td><strong>{{ $t->descricao }}</strong></td>
                <td><span class="badge badge-outline">{{ strtoupper($t->forma_pagamento) }}</span></td>
                <td style="font-weight: 700; color: #10b981;">R$ {{ number_format($t->valor, 2, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="empty-state">Nenhum pagamento registrado no CPF deste cliente.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <h4 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary-dark);">Todas as Fórmulas Vinculadas (Ordem Cronológica)</h4>
      <div class="table-container">
        <table>
          <thead>
            <tr><th>Data</th><th>Fórmula Solicitada</th><th>Prescritor (Médico)</th><th>Preço</th><th>Situação Atual</th></tr>
          </thead>
          <tbody>
            @forelse($cliente->receitas as $r)
              <tr>
                <td>{{ $r->data->format('d/m/Y') }}</td>
                <td><strong>{{ $r->nome_formula }}</strong></td>
                <td>{{ $r->medico }}</td>
                <td>R$ {{ number_format($r->orcamento, 2, ',', '.') }}</td>
                <td><span class="badge {{ $r->statusBadgeClass() }}">{{ $r->statusLabel() }}</span></td>
              </tr>
            @empty
              <tr><td colspan="5" class="empty-state">Nenhuma receita prescrita cadastrada para este cliente.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection