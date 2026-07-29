<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo $__env->yieldContent('titulo', 'Painel'); ?> - Princípio Ativo</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
  <?php echo $__env->yieldPushContent('estilos'); ?>
</head>
<body>
  <div class="dashboard">
    <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <main class="main-content">
      <div class="top-bar">
        <button class="mobile-sidebar-btn" data-toggle-sidebar>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <h1><?php echo $__env->yieldContent('titulo', 'Painel'); ?></h1>
        <div></div>
      </div>

      <div class="page-content">
        
        <?php if(session('sucesso')): ?>
          <div class="alert alert-success" style="margin-bottom:1rem;"><?php echo e(session('sucesso')); ?></div>
        <?php endif; ?>

        <?php if(session('erro')): ?>
          <div class="alert alert-danger" style="margin-bottom:1rem;"><?php echo e(session('erro')); ?></div>
        <?php endif; ?>

        

        
        <?php echo $__env->yieldContent('conteudo'); ?>
      </div>
    </main>
  </div>

  <?php echo $__env->yieldContent('modais'); ?>

  <script src="<?php echo e(asset('js/app.js')); ?>"></script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Mariana\Documents\GitHub\Sistema Princípio Ativo - TCC\resources\views/layouts/app.blade.php ENDPATH**/ ?>