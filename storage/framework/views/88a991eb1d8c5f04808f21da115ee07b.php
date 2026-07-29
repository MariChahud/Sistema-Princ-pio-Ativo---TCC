

<?php $__env->startSection('titulo', 'Produtos e Estoque'); ?>

<?php $__env->startSection('conteudo'); ?>
  <a href="<?php echo e(route('produtos.index')); ?>" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Voltar para Produtos
  </a>

  <div class="card">
    <div class="card-header">
      <div>
        <div style="display:flex;align-items:center;gap:12px;">
          <span style="font-size:1rem;font-weight:700;color:var(--primary-dark);"><?php echo e($produto->nome); ?></span>
          <?php if($produto->abaixoDoMinimo()): ?>
            <span class="alerta-minimo" style="display:inline-flex;align-items:center;gap:5px;background:rgba(230,126,34,0.1);color:var(--warning);border:1px solid rgba(230,126,34,0.3);border-radius:9999px;padding:3px 10px;font-size:0.75rem;font-weight:700;">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
              Alerta de Quantidade Mínima
            </span>
          <?php endif; ?>
        </div>
        <span style="font-size:0.75rem;color:var(--muted);">DCB: <?php echo e($produto->dcb); ?> | Estoque atual: <?php echo e(rtrim(rtrim(number_format($produto->estoque_atual,4,'.',''),'0'),'.')); ?> <?php echo e($produto->unidade); ?> | Mínimo: <?php echo e($produto->estoque_minimo); ?> <?php echo e($produto->unidade); ?></span>
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
            <?php $__empty_1 = true; $__currentLoopData = $produto->lotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                $dias = $lote->diasParaVencer();
                $vc = $dias <= 30 ? 'badge-danger' : ($dias <= 90 ? 'badge-warning' : 'badge-success');
              ?>
              <tr style="<?php echo e(!$lote->ativo ? 'opacity:0.5;' : ''); ?>">
                <td><strong><?php echo e($lote->numero); ?></strong></td>
                <td><?php echo e(rtrim(rtrim(number_format($lote->quantidade,4,'.',''),'0'),'.')); ?></td>
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
                    <form method="POST" action="<?php echo e(route('lotes.desativar', $lote)); ?>" data-confirm="Desativar este lote?" style="display:inline;">
                      <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                      <button class="btn btn-sm btn-danger">Desativar</button>
                    </form>
                  <?php else: ?>
                    <form method="POST" action="<?php echo e(route('lotes.ativar', $lote)); ?>" style="display:inline;">
                      <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                      <button class="btn btn-sm btn-outline">Reativar</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="6" class="empty-state">Sem lotes cadastrados para este produto</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  <?php echo $__env->make('produtos.modais', ['produtoFixo' => $produto], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.querySelectorAll('[data-lote-novo]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('loteForm').reset();
    openModal('loteModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views/produtos/lotes.blade.php ENDPATH**/ ?>