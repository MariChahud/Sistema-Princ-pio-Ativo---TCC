

<?php $__env->startSection('titulo', 'Produtos e Estoque'); ?>

<?php $__env->startSection('conteudo'); ?>
  
  <div class="tabs">
    <button class="tab active" data-tab="produtosTab">Produtos</button>
    <button class="tab" data-tab="lotesTab">Lotes</button>
  </div>

  
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
          <form method="GET" action="<?php echo e(route('produtos.index')); ?>" class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="<?php echo e($busca); ?>" placeholder="Buscar produtos..." onchange="this.form.submit()">
          </form>
        </div>
        <div class="table-container">
          <table>
            <thead>
              <tr><th>Nome</th><th>DCB</th><th>Preço Base</th><th>Estoque</th><th>Mínimo</th><th>Ações</th></tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $produtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $alerta = $produto->lotesValidadeProxima()->count() > 0; ?>
                <tr>
                  <td>
                    <a href="<?php echo e(route('produtos.lotes', $produto)); ?>" class="produto-nome-link" style="font-weight:700;"><?php echo e($produto->nome); ?></a>
                    <?php if($alerta): ?>
                      <span title="Lote com validade próxima" style="color:#f59e0b;margin-left:6px;vertical-align:middle;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                      </span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($produto->dcb); ?></td>
                  <td>R$ <?php echo e(number_format($produto->preco_base, 2, ',', '.')); ?>/<?php echo e($produto->unidade); ?></td>
                  <td>
                    
                    <span class="badge <?php echo e($produto->abaixoDoMinimo() ? 'badge-danger' : 'badge-success'); ?>">
                      <?php echo e(rtrim(rtrim(number_format($produto->estoque_atual, 4, '.', ''), '0'), '.')); ?> <?php echo e($produto->unidade); ?>

                    </span>
                  </td>
                  <td><?php echo e($produto->estoque_minimo); ?> <?php echo e($produto->unidade); ?></td>
                  <td class="table-actions">
                    <button class="btn btn-sm btn-outline"
                      data-produto-editar
                      data-id="<?php echo e($produto->id); ?>"
                      data-nome="<?php echo e($produto->nome); ?>"
                      data-dcb="<?php echo e($produto->dcb); ?>"
                      data-unidade="<?php echo e($produto->unidade); ?>"
                      data-preco="<?php echo e($produto->preco_base); ?>"
                      data-min="<?php echo e($produto->estoque_minimo); ?>">Editar</button>
                    <form method="POST" action="<?php echo e(route('produtos.destroy', $produto)); ?>" data-confirm="Tem certeza que deseja excluir este produto?" style="display:inline;">
                      <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                      <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="empty-state">Nenhum produto encontrado</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  
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
              <?php $__empty_1 = true; $__currentLoopData = $lotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                  $dias = $lote->diasParaVencer(); 
                  $vc = $dias <= 30 ? 'badge-danger' : ($dias <= 90 ? 'badge-warning' : 'badge-success'); 
                ?>
                <tr style="<?php echo e(!$lote->ativo ? 'opacity:0.5;' : ''); ?>">
                  <td><?php echo e($lote->numero); ?></td>
                  <td><?php echo e($lote->produto->nome ?? 'N/A'); ?></td>
                  <td><?php echo e(rtrim(rtrim(number_format($lote->quantidade, 4, '.', ''), '0'), '.')); ?></td>
                  <td><span class="badge <?php echo e($vc); ?>"><?php echo e($lote->validade->format('d/m/Y')); ?></span></td>
                  <td><?php echo e($lote->fornecedor); ?></td>
                  <td>
                    <?php if($lote->ativo): ?>
                      <span class="badge badge-success">Ativo</span>
                    <?php else: ?>
                      <span class="badge badge-secondary">Desativado</span>
                    <?php endif; ?>
                  </td>
                  <td class="table-actions">
                    <?php if($lote->ativo): ?>
                      <form method="POST" action="<?php echo e(route('lotes.desativar', $lote)); ?>" data-confirm="Desativar este lote? Ele não aparecerá ao registrar receitas." style="display:inline;">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-sm btn-danger">Desativar</button>
                      </form>
                    <?php else: ?>
                      <form method="POST" action="<?php echo e(route('lotes.ativar', $lote)); ?>" style="display:inline;">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-sm btn-outline">Reativar</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="empty-state">Nenhum lote cadastrado</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  <?php echo $__env->make('produtos.modais', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const produtoForm = document.getElementById('produtoForm');
  const produtoBase = "<?php echo e(url('produtos')); ?>";

  // Gatilhos do Modal de Produtos
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
    document.getElementById('produtoModalTitle').textContent = 'Editar Produto';
    document.getElementById('produtoNome').value = b.dataset.nome;
    document.getElementById('produtoDCB').value = b.dataset.dcb;
    document.getElementById('produtoUnidade').value = b.dataset.unidade;
    document.getElementById('produtoPreco').value = b.dataset.preco;
    document.getElementById('produtoEstoqueMin').value = b.dataset.min;
    openModal('produtoModal');
  }));

  // Gatilho do Modal de Lotes
  document.querySelectorAll('[data-lote-novo]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('loteForm').reset();
    openModal('loteModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\produtos\index.blade.php ENDPATH**/ ?>