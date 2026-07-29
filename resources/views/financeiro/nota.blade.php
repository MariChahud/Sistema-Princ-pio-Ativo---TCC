@extends('layouts.app')

@section('titulo', 'Emissão de Nota de Venda')

@section('conteudo')
  <div class="no-print" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('financeiro.index') }}" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Voltar para o Caixa
    </a>
    <button onclick="window.print()" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
      Imprimir Recibo / Nota
    </button>
  </div>

  {{-- Estrutura de Cupom Não Fiscal para Impressão Térmica ou A4 --}}
  <div class="card" style="max-width: 600px; margin: 0 auto; border: 1px dashed var(--border); font-family: monospace;">
    <div class="card-content" style="padding: 2rem;">
      <div style="text-align: center; margin-bottom: 1.5rem; border-bottom: 1px dashed var(--border); padding-bottom: 1rem;">
        <h2 style="margin: 0 0 0.25rem 0; font-weight: 800; letter-spacing: 1px;">PRINCÍPIO ATIVO</h2>
        <p style="margin: 0; font-size: 0.85rem; color: var(--muted);">Farmácia de Manipulação e Magistral</p>
        <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem;">CNPJ: 00.000.000/0001-00 — IE: Isento</p>
      </div>

      <div style="font-size: 0.85rem; margin-bottom: 1.5rem; line-height: 1.4;">
        <p style="margin: 0;"><strong>DOC. AUXILIAR DE VENDA:</strong> #{{ str_pad($transacoes->first()->id, 5, '0', STR_PAD_LEFT) }}{{ $transacoes->count() > 1 ? ' (+' . ($transacoes->count() - 1) . ')' : '' }}</p>
        <p style="margin: 0;"><strong>DATA EMISSÃO:</strong> {{ \Carbon\Carbon::parse($transacoes->first()->data)->format('d/m/Y H:i') }}</p>
        <p style="margin: 0;"><strong>CLIENTE:</strong> {{ $cliente->nome ?? 'Consumidor Geral' }}</p>
        <p style="margin: 0;"><strong>CPF:</strong> {{ $cliente->cpf ?? '—' }}</p>
      </div>

      <div style="border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem; margin-bottom: 1rem;">
        <h4 style="margin: 0 0 0.5rem 0; text-transform: uppercase; font-size: 0.9rem;">Descrição dos Itens Manipulados</h4>

        @foreach($transacoes as $transacao)
          @if($transacao->receita)
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.25rem; font-weight: 700;">
              <span>1x {{ $transacao->receita->nome_formula }} ({{ $transacao->receita->qtd_capsulas }} caps)</span>
              <span>R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
            </div>
            <div style="padding-left: 1rem; font-size: 0.8rem; color: var(--muted); line-height: 1.3; margin-bottom: 0.75rem;">
              @foreach($transacao->receita->itens as $item)
                <div>— {{ $item->produto->nome }} (Lote: {{ $item->lote->numero }}) - {{ number_format($item->dosagem_mg, 2, ',', '.') }}mg</div>
              @endforeach
            </div>
          @else
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem;">
              <span>{{ $transacao->descricao }}</span>
              <span>R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
            </div>
          @endif
        @endforeach
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; font-size: 1.15rem; font-weight: 800; margin-top: 1.5rem; padding-top: 0.5rem; border-top: 1px dashed var(--border);">
        <span>TOTAL RECEBIDO</span>
        <span>R$ {{ number_format($totalGeral, 2, ',', '.') }}</span>
      </div>

      <div style="font-size: 0.85rem; margin-top: 0.5rem; display: flex; justify-content: space-between; color: var(--muted);">
        <span>FORMA DE RECOLHIMENTO:</span>
        <span style="font-weight: 700;">{{ strtoupper($transacoes->first()->forma_pagamento) }}</span>
      </div>

      <div style="text-align: center; margin-top: 3rem; font-size: 0.8rem; color: var(--muted); border-top: 1px solid var(--border); padding-top: 1rem;">
        <p style="margin: 0;">Obrigado pela preferência e confiança!</p>
        <p style="margin: 0.25rem 0 0 0;">Princípio Ativo — Qualidade e Precisão em Cada Dose.</p>
      </div>
    </div>
  </div>

  {{-- CSS extra para ocultar elementos do sistema no momento da impressão --}}
  <style>
    @media print {
      body { background: #fff; color: #000; padding: 0; margin: 0; }
      .no-print, .sidebar, .header, header, nav, .back-btn { display: none !important; }
      .main-content, .content, main { padding: 0 !important; margin: 0 !important; }
      .card { border: none !important; box-shadow: none !important; max-width: 100% !important; }
    }
  </style>
@endsection