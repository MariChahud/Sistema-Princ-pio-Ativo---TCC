

<?php $__env->startSection('titulo', 'Módulo Financeiro e Frente de Caixa'); ?>

<?php $__env->startPush('estilos'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('conteudo'); ?>
  
  <div class="cards-financeiros">
    <div class="card-indicador" style="border-left: 4px solid #10b981;">
      <div class="indicador-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">R$</div>
      <div class="indicador-info">
        <span>Total de Entradas</span>
        <strong>R$ <?php echo e(number_format($totalEntradas, 2, ',', '.')); ?></strong>
      </div>
    </div>
    <div class="card-indicador" style="border-left: 4px solid #ef4444;">
      <div class="indicador-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">R$</div>
      <div class="indicador-info">
        <span>Total de Saídas</span>
        <strong>R$ <?php echo e(number_format($totalSaidas, 2, ',', '.')); ?></strong>
      </div>
    </div>
    <div class="card-indicador" style="border-left: 4px solid <?php echo e($saldoAtual >= 0 ? '#10b981' : '#ef4444'); ?>;">
      <div class="indicador-icon" style="background: <?php echo e($saldoAtual >= 0 ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)'); ?>; color: <?php echo e($saldoAtual >= 0 ? '#10b981' : '#ef4444'); ?>;">🧮</div>
      <div class="indicador-info">
        <span>Saldo Atual</span>
        <strong>R$ <?php echo e(number_format($saldoAtual, 2, ',', '.')); ?></strong>
      </div>
    </div>
  </div>

  
  <?php if(request()->has('receita_venda')): ?>
    @php $receitaVenda = \App\Models\Receita::with(['cliente', 'itens.produto', 'itens.lote'])->find(request('receita_venda')); @php
    <?php if($receitaVenda): ?>
      <div class="card" style="border: 2px solid #10b981; margin-bottom: 1.5rem;">
        <div class="card-header" style="background: rgba(16,185,129,0.05);">
          <h3 class="card-title" style="color: #065f46;">Checkout de Venda Ativo</h3>
          <a href="<?php echo e(route('financeiro.index')); ?>" class="btn btn-sm btn-outline">Cancelar</a>
        </div>
        <div class="card-content pdv-container">
          <div>
            <h4 style="margin-bottom: 0.5rem;">Fórmula: <strong><?php echo e($receitaVenda->nome_formula); ?></strong></h4>
            <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 1rem;">Ordem de manipulação #<?php echo e(str_pad($receitaVenda->id, 3, '0', STR_PAD_LEFT)); ?> | Cliente: <?php echo e($receitaVenda->cliente->nome); ?></p>
            
            <div class="table-container">
              <table>
                <thead><tr><th>Componente</th><th>Lote</th><th>Dosagem</th></tr></thead>
                <tbody>
                  <?php $__currentLoopData = $receitaVenda->itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><?php echo e($item->produto->nome ?? 'N/A'); ?></td>
                      <td><span class="badge badge-secondary"><?php echo e($item->lote->numero ?? 'N/A'); ?></span></td>
                      <td><?php echo e(number_format($item->dosagem_mg, 2, ',', '.')); ?> mg</td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
          </div>
          <div style="background: var(--background-light); padding: 1.25rem; border-radius: var(--radius); border: 1px solid var(--border);">
            <span style="font-size: 0.85rem; color: var(--muted); display: block; font-weight: 600; text-transform: uppercase;">Valor a Pagar:</span>
            <strong style="font-size: 2rem; color: #10b981; display: block; margin-bottom: 1.25rem;">R$ <?php echo e(number_format($receitaVenda->orcamento, 2, ',', '.')); ?></strong>
            
            <form method="POST" action="<?php echo e(route('financeiro.venda.confirmar', $receitaVenda)); ?>">
              <?php echo csrf_field(); ?>
              <div class="form-group">
                <label for="forma_pagamento" style="font-weight: 700;">Forma de Pagamento</label>
                <select name="forma_pagamento" id="forma_pagamento" required>
                  <option value="pix">PIX</option>
                  <option value="cartao">Cartão</option>
                  <option value="dinheiro">Dinheiro</option>
                  <option value="boleto">Boleto</option>
                  <option value="transferencia">Transferência</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary" style="background: #10b981; border-color: #10b981; width: 100%; justify-content: center; height: 42px; font-weight: 700;">
                Confirmar Recebimento e Emitir Nota
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  <?php else: ?>
    
    <div class="card" style="margin-bottom: 1.5rem; background: var(--background-light);">
      <div class="card-content" style="padding: 1rem;">
        <form method="POST" action="<?php echo e(route('financeiro.venda.buscar')); ?>" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
          <?php echo csrf_field(); ?>
          <div class="form-group" style="margin: 0; flex: 1; min-width: 250px;">
            <label for="buscar_cpf" style="font-weight: 700; color: var(--primary-dark);">Balcão de Atendimento — Processar Venda de Fórmula</label>
            <input type="text" id="buscar_cpf" name="cpf" placeholder="Insira o CPF do cliente para buscar receita pronta (Pesada)..." data-mask="cpf" required>
          </div>
          <button type="submit" class="btn btn-primary" style="height: 38px;">
            Buscar Receitas Prontas
          </button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  
  <div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div class="tabs-bar" style="margin: 0; border: none;">
        <a class="tab <?php echo e(!request()->has('view_clientes') ? 'active' : ''); ?>" href="<?php echo e(route('financeiro.index')); ?>">Fluxo de Caixa Geral</a>
        <a class="tab <?php echo e(request()->has('view_clientes') ? 'active' : ''); ?>" href="<?php echo e(route('financeiro.index', ['view_clientes' => 1])); ?>">Histórico por Clientes</a>
      </div>
      <?php if(!request()->has('view_clientes')): ?>
        <button class="btn btn-outline btn-sm" data-transacao-nova>+ Lançamento Avulso</button>
      <?php endif; ?>
    </div>

    <div class="card-content">
      <?php if(!request()->has('view_clientes')): ?>
        <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem;">
          <?php $__currentLoopData = ['todos' => 'Todos', 'entrada' => 'Apenas Entradas', 'saida' => 'Apenas Saídas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('financeiro.index', ['tipo' => $v])); ?>" class="btn btn-sm <?php echo e($filtro === $v ? 'btn-primary' : 'btn-outline'); ?>"><?php echo e($l); ?></a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr><th>Data</th><th>Descrição</th><th>Categoria</th><th>Pagamento</th><th>Valor</th><th>Ações</th></tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $transacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td><?php echo e(\Carbon\Carbon::parse($t->data)->format('d/m/Y')); ?></td>
                  <td><strong><?php echo e($t->descricao); ?></strong></td>
                  <td><span class="badge badge-secondary"><?php echo e(ucfirst($t->categoria)); ?></span></td>
                  <td><span class="badge badge-outline"><?php echo e(strtoupper($t->forma_pagamento)); ?></span></td>
                  <td style="font-weight: 700; color: <?php echo e($t->tipo === 'entrada' ? '#10b981' : '#ef4444'); ?>;">
                    <?php echo e($t->tipo === 'entrada' ? '+' : '-'); ?> R$ <?php echo e(number_format($t->valor, 2, ',', '.')); ?>

                  </td>
                  <td class="table-actions">
                    <form method="POST" action="<?php echo e(route('financeiro.transacao.destroy', $t)); ?>" data-confirm="Deseja estornar/excluir este lançamento?" style="display:inline;">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="empty-state">Nenhum lançamento financeiro registrado.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="table-container">
          <table>
            <thead><tr><th>Cliente</th><th>Total de Fórmulas</th><th>Ações</th></tr></thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td><strong><?php echo e($c->nome); ?></strong><br><small style="color:var(--muted)">CPF: <?php echo e($c->cpf); ?></small></td>
                  <td><?php echo e($c->receitas_count); ?> fórmulas vinculadas</td>
                  <td class="table-actions">
                    <a href="<?php echo e(route('financeiro.historico', $c)); ?>" class="btn btn-sm btn-outline">Ver Extrato Financeiro</a>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3" class="empty-state">Nenhum cliente listado.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  <div class="modal-overlay" id="transacaoModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title">Novo Lançamento Financeiro</h3>
        <button class="modal-close" data-close-modal="transacaoModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <form method="POST" action="<?php echo e(route('financeiro.transacao.store')); ?>">
        <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.querySelectorAll('[data-transacao-nova]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('t_data').value = new Date().toISOString().split('T')[0];
    openModal('transacaoModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\financeiro\index.blade.php ENDPATH**/ ?>