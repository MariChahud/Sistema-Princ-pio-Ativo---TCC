@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')
  {{ Cards de Indicadores}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Total Clientes</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div class="stat-value">{{ $totalClientes }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Produtos Ativos</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
      </div>
      <div class="stat-value">{{ $totalProdutos }}</div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Receitas Pendentes</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="stat-value">{{ $receitasPendentes }}</div>
      @if ($receitasPendentes > 0)
        <div class="stat-change negative">Atenção necessária</div>
      @endif
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Saldo do Mês</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="stat-value">R$ {{ number_format($saldoMes, 2, ',', '.') }}</div>
    </div>
  </div>

  {{Seção inferior dividida em 2 colunas}}
  <div class="grid-2">
    {{-- Coluna 1: Centralização de Alertas de Segurança Farmacêutica --}}
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Alertas</h3>
      </div>
      <div class="card-content">
        @forelse ($alertas as $alerta)
          <div class="alert alert-{{ $alerta['tipo'] }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
              <strong>{{ $alerta['titulo'] }}</strong>
              <p>{{ $alerta['mensagem'] }}</p>
            </div>
          </div>
        @empty
          <p class="empty-state">Nenhum alerta no momento</p>
        @endforelse
      </div>
    </div>

    
  </div>
@endsection