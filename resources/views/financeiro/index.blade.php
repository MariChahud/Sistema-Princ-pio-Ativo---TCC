@extends('layouts.app')

@section('titulo', 'Módulo Financeiro e Frente de Caixa')

@push('estilos')
<style>
  .cards-financeiros { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
  .card-indicador { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; }
  .indicador-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
  .indicador-info span { display: block; font-size: 0.85rem; color: var(--muted); font-weight: 500; }
  .indicador-info strong { font-size: 1.35rem; font-weight: 800; color: var(--text-dark); }
  .tabs-bar { display: flex; border-bottom: 1px solid var(--border); margin-bottom: 1.5rem; gap: 0.5rem; }
  .pdv-container { display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; align-items: start; margin-bottom: 1.5rem; }
  @media(max-width: 768px) { .pdv-container { grid-template-columns: 1fr; } }
</style>
@endpush

@section('conteudo')
  {{-- Indicadores de Caixa --}}
  <div class="cards-financeiros">
    <div class="card-indicador" style="border-left: 4px solid #10b981;">
      <div class="indicador-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">R$</div>
      <div class="indicador-info">
        <span>Total de Entradas</span>
        <strong>R$ {{ number_format($totalEntradas, 2, ',', '.') }}</strong>
      </div>
    </div>
    <div class="card-indicador" style="border-left: 4px solid #ef4444;">
      <div class="indicador-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">R$</div>
      <div class="indicador-info">
        <span>Total de Saídas</span>
        <strong>R$ {{ number_format($totalSaidas, 2, ',', '.') }}</strong>
      </div>
    </div>
    <div class="card-indicador" style="border-left: 4px solid {{ $saldoAtual >= 0 ? '#10b981' : '#ef4444' }};">
      <div class="indicador-icon" style="background: {{ $saldoAtual >= 0 ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }}; color: {{ $saldoAtual >= 0 ? '#10b981' : '#ef4444' }};">🧮</div>
      <div class="indicador-info">
        <span>Saldo Atual</span>
        <strong>R$ {{ number_format($saldoAtual, 2, ',', '.') }}</strong>
      </div>
    </div>
  </div>

  {{-- Painel de Checkout do PDV --}}
  @if($clienteVenda)
    @if($receitasDisponiveis->isNotEmpty())
      <div class="card" style="border: 2px solid #10b981; margin-bottom: 1.5rem;">
        <div class="card-header" style="background: rgba(16,185,129,0.05);">
          <div>
            <h3 class="card-title" style="color: #065f46;">Checkout de Venda — {{ $clienteVenda->nome }}</h3>
            <p style="margin:0; font-size:0.85rem; color: var(--muted);">CPF: {{ $clienteVenda->cpf }} — selecione uma ou mais receitas para faturar juntas</p>
          </div>
          <a href="{{ route('financeiro.index') }}" class="btn btn-sm btn-outline">Cancelar</a>
        </div>
        <div class="card-content">
          <form method="POST" action="{{ route('financeiro.venda.confirmar') }}" id="vendaForm">
            @csrf

            <div class="table-container" style="margin-bottom: 1rem;">
              <table>
                <thead>
                  <tr>
                    <th style="width:40px;"></th>
                    <th>Fórmula</th>
                    <th>Ordem</th>
                    <th>Data</th>
                    <th>Itens</th>
                    <th style="text-align:right;">Valor</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($receitasDisponiveis as $receita)
                    <tr>
                      <td>
                        <input type="checkbox" name="receitas[]" value="{{ $receita->id }}"
                               class="checkbox-receita" data-valor="{{ $receita->orcamento }}"
                               id="receita-{{ $receita->id }}">
                      </td>
                      <td>
                        <label for="receita-{{ $receita->id }}" style="cursor:pointer;">
                          <strong>{{ $receita->nome_formula }}</strong>
                        </label>
                      </td>
                      <td>#{{ str_pad($receita->id, 3, '0', STR_PAD_LEFT) }}</td>
                      <td>{{ $receita->data->format('d/m/Y') }}</td>
                      <td style="font-size:0.8rem; color: var(--muted);">
                        @foreach($receita->itens as $item)
                          {{ $item->produto->nome ?? 'N/A' }} ({{ $item->lote->numero ?? 'N/A' }})@if(!$loop->last), @endif
                        @endforeach
                      </td>
                      <td style="text-align:right; font-weight:700;">R$ {{ number_format($receita->orcamento, 2, ',', '.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; gap:1.5rem; flex-wrap:wrap; background: var(--background-light); padding: 1.25rem; border-radius: var(--radius); border: 1px solid var(--border);">
              <div style="flex:1; min-width:220px;">
                <label for="forma_pagamento" style="font-weight: 700; display:block; margin-bottom:0.5rem;">Forma de Pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" required>
                  <option value="pix">PIX</option>
                  <option value="cartao">Cartão</option>
                  <option value="dinheiro">Dinheiro</option>
                  <option value="boleto">Boleto</option>
                  <option value="transferencia">Transferência</option>
                </select>
              </div>
              <div style="text-align:right;">
                <span style="font-size: 0.85rem; color: var(--muted); display: block; font-weight: 600; text-transform: uppercase;">Total Selecionado:</span>
                <strong id="totalSelecionado" style="font-size: 2rem; color: #10b981;">R$ 0,00</strong>
              </div>
              <button type="submit" class="btn btn-primary" id="btnConfirmarVenda" disabled
                      style="background: #10b981; border-color: #10b981; height: 42px; font-weight: 700; padding: 0 1.5rem;">
                Confirmar Recebimento e Emitir Nota
              </button>
            </div>
          </form>
        </div>
      </div>
    @else
      <div class="card" style="border: 2px solid #f59e0b; margin-bottom: 1.5rem;">
        <div class="card-content" style="display:flex; justify-content:space-between; align-items:center;">
          <p style="margin:0;">
            <strong>{{ $clienteVenda->nome }}</strong> não possui nenhuma receita com status <strong>Pesado</strong> disponível para venda.
          </p>
          <a href="{{ route('financeiro.index') }}" class="btn btn-sm btn-outline">Voltar</a>
        </div>
      </div>
    @endif
  @else
    {{-- Input de busca rápida do PDV --}}
    <div class="card" style="margin-bottom: 1.5rem; background: var(--background-light);">
      <div class="card-content" style="padding: 1rem;">
        <form method="POST" action="{{ route('financeiro.venda.buscar') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
          @csrf
          <div class="form-group" style="margin: 0; flex: 1; min-width: 250px;">
            <label for="buscar_cpf" style="font-weight: 700; color: var(--primary-dark);">Balcão de Atendimento — Processar Venda de Fórmula(s)</label>
            <input type="text" id="buscar_cpf" name="cpf" placeholder="Insira o CPF do cliente para buscar receitas prontas (Pesadas)..." data-mask="cpf" required>
          </div>
          <button type="submit" class="btn btn-primary" style="height: 38px;">
            Buscar Receitas Prontas
          </button>
        </form>
      </div>
    </div>
  @endif

  {{-- Histórico de Lançamentos Contábeis --}}
  <div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div class="tabs-bar" style="margin: 0; border: none;">
        <a class="tab {{ !request()->has('view_clientes') ? 'active' : '' }}" href="{{ route('financeiro.index') }}">Fluxo de Caixa Geral</a>
        <a class="tab {{ request()->has('view_clientes') ? 'active' : '' }}" href="{{ route('financeiro.index', ['view_clientes' => 1]) }}">Histórico por Clientes</a>
      </div>
      @if(!request()->has('view_clientes'))
        <button class="btn btn-outline btn-sm" data-transacao-nova>+ Lançamento Avulso</button>
      @endif
    </div>

    <div class="card-content">
      @if(!request()->has('view_clientes'))
        <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem;">
          @foreach(['todos' => 'Todos', 'entrada' => 'Apenas Entradas', 'saida' => 'Apenas Saídas'] as $v => $l)
            <a href="{{ route('financeiro.index', ['tipo' => $v]) }}" class="btn btn-sm {{ $filtro === $v ? 'btn-primary' : 'btn-outline' }}">{{ $l }}</a>
          @endforeach
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr><th>Data</th><th>Descrição</th><th>Categoria</th><th>Pagamento</th><th>Valor</th><th>Ações</th></tr>
            </thead>
            <tbody>
              @forelse($transacoes as $t)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($t->data)->format('d/m/Y') }}</td>
                  <td><strong>{{ $t->descricao }}</strong></td>
                  <td><span class="badge badge-secondary">{{ ucfirst($t->categoria) }}</span></td>
                  <td><span class="badge badge-outline">{{ strtoupper($t->forma_pagamento) }}</span></td>
                  <td style="font-weight: 700; color: {{ $t->tipo === 'entrada' ? '#10b981' : '#ef4444' }};">
                    {{ $t->tipo === 'entrada' ? '+' : '-' }} R$ {{ number_format($t->valor, 2, ',', '.') }}
                  </td>
                  <td class="table-actions">
                    <form method="POST" action="{{ route('financeiro.transacao.destroy', $t) }}" data-confirm="Deseja estornar/excluir este lançamento?" style="display:inline;">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="empty-state">Nenhum lançamento financeiro registrado.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @else
        <div class="table-container">
          <table>
            <thead><tr><th>Cliente</th><th>Total de Fórmulas</th><th>Ações</th></tr></thead>
            <tbody>
              @forelse($clientes as $c)
                <tr style="border-bottom: 1px solid var(--border);">
                  <td style="border-bottom: none;"><strong>{{ $c->nome }}</strong><br><small style="color:var(--muted)">CPF: {{ $c->cpf }}</small></td>
                  <td style="border-bottom: none;">{{ $c->receitas_count }} fórmulas vinculadas</td>
                  <td class="table-actions" style="border-bottom: none;">
                    <a href="{{ route('financeiro.historico', $c) }}" class="btn btn-sm btn-outline">Ver Extrato Financeiro</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="empty-state">Nenhum cliente listado.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection

@section('modais')
  <div class="modal-overlay" id="transacaoModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title">Novo Lançamento Financeiro</h3>
        <button class="modal-close" data-close-modal="transacaoModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form method="POST" action="{{ route('financeiro.transacao.store') }}">
        @csrf
        <div class="modal-body">
          <div class="grid-2">
            <div class="form-group">
              <label for="t_tipo">Tipo do Movimento</label>
              <select name="tipo" id="t_tipo" required>
                <option value="saida">Saída (Despesa / Custo)</option>
                <option value="entrada">Entrada (Aporte / Outros)</option>
              </select>
            </div>
            <div class="form-group">
              <label for="t_valor">Valor (R$)</label>
              <input type="number" id="t_valor" name="valor" step="0.01" min="0.01" placeholder="0,00" required>
            </div>
          </div>
          <div class="form-group">
            <label for="t_desc">Descrição do Lançamento</label>
            <input type="text" id="t_desc" name="descricao" placeholder="Ex: Pagamento de fornecedor de insumos" required>
          </div>
          <div class="grid-2">
            <div class="form-group">
              <label for="t_cat">Categoria</label>
              <select name="categoria" id="t_cat" required>
                <option value="fornecedores">Fornecedores</option>
                <option value="salarios">Salários e Proventos</option>
                <option value="aluguel">Aluguel e Taxas</option>
                <option value="utilidades">Utilidades (Água, Luz, Internet)</option>
                <option value="vendas">Vendas / Receitas</option>
                <option value="outros">Outros Lançamentos</option>
              </select>
            </div>
            <div class="form-group">
              <label for="t_forma">Forma de Movimentação</label>
              <select name="forma_pagamento" id="t_forma" required>
                <option value="pix">PIX</option>
                <option value="cartao">Cartão</option>
                <option value="dinheiro">Dinheiro</option>
                <option value="boleto">Boleto</option>
                <option value="transferencia">Transferência</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="t_data">Data do Lançamento</label>
            <input type="date" id="t_data" name="data" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="transacaoModal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Lançamento</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('[data-transacao-nova]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('t_data').value = new Date().toISOString().split('T')[0];
    openModal('transacaoModal');
  }));

  // ── Checkout de venda: soma o total das receitas selecionadas ────────
  const checkboxesReceita = document.querySelectorAll('.checkbox-receita');
  const totalEl = document.getElementById('totalSelecionado');
  const btnConfirmar = document.getElementById('btnConfirmarVenda');

  function atualizarTotalVenda() {
    let total = 0;
    let algumSelecionado = false;

    checkboxesReceita.forEach(cb => {
      if (cb.checked) {
        total += parseFloat(cb.dataset.valor) || 0;
        algumSelecionado = true;
      }
    });

    if (totalEl) totalEl.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    if (btnConfirmar) btnConfirmar.disabled = !algumSelecionado;
  }

  checkboxesReceita.forEach(cb => cb.addEventListener('change', atualizarTotalVenda));
  atualizarTotalVenda();
</script>
@endpush