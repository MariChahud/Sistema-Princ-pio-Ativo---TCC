

<?php $__env->startSection('titulo', 'Dashboard'); ?>

<?php $__env->startSection('conteudo'); ?>
  
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Total Clientes</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div class="stat-value"><?php echo e($totalClientes); ?></div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Produtos Ativos</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
      </div>
      <div class="stat-value"><?php echo e($totalProdutos); ?></div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Receitas Pendentes</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="stat-value"><?php echo e($receitasPendentes); ?></div>
      <?php if($receitasPendentes > 0): ?>
        <div class="stat-change negative">Atenção necessária</div>
      <?php endif; ?>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <span class="stat-label">Saldo do Mês</span>
        <svg class="stat-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      
      <div class="stat-value">R$ <?php echo e(number_format($saldoMes, 2, ',', '.')); ?></div>
    </div>
  </div>

  
  <div class="grid-2">
    
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Alertas</h3>
      </div>
      <div class="card-content">
        <?php $__empty_1 = true; $__currentLoopData = $alertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alerta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
          <div class="alert alert-<?php echo e($alerta['tipo']); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
              <strong><?php echo e($alerta['titulo']); ?></strong>
              <p><?php echo e($alerta['mensagem']); ?></p>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <p class="empty-state">Nenhum alerta no momento</p>
        <?php endif; ?>
      </div>
    </div>

    
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\GitHub\Sistema Princípio Ativo - TCC\resources\views/dashboard.blade.php ENDPATH**/ ?>