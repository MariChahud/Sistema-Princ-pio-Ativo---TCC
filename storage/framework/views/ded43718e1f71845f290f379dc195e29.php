<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Princípio Ativo</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-header">
        <a href="<?php echo e(route('home')); ?>">
          <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Princípio Ativo">
        </a>
        <h1>Bem-vindo de volta</h1>
        <p>Faça login para acessar o sistema</p>
      </div>

      
      <?php if($errors->any()): ?>
        <div class="error-message" style="display:flex;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <?php echo e($errors->first()); ?>

        </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="seu@email.com" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <div class="input-with-icon">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <button type="button" class="input-icon" data-toggle-password>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="form-actions">
          <label style="display:flex;align-items:center;gap:6px;font-size:0.875rem;">
            <input type="checkbox" name="remember"> Lembrar de mim
          </label>
          <a href="#" data-open-modal="forgotPasswordModal">Esqueceu a senha?</a>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>
      </form>

      <div class="login-footer">
        <a href="<?php echo e(route('home')); ?>">← Voltar para o início</a>
      </div>
    </div>
  </div>

  
  <div class="modal-overlay" id="forgotPasswordModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title">Recuperar senha</h3>
        <button class="modal-close" data-close-modal="forgotPasswordModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body">
        <p style="margin-bottom:1rem;color:var(--muted);">A recuperação de senha será tratada pelo backend (rota de reset do Laravel). Entre em contato com o administrador.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-close-modal="forgotPasswordModal">Fechar</button>
      </div>
    </div>
  </div>

  <script src="<?php echo e(asset('js/app.js')); ?>"></script>
</body>
</html><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\auth\login.blade.php ENDPATH**/ ?>