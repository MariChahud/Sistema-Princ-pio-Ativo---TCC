

<?php $__env->startSection('titulo', 'Usuários'); ?>

<?php $__env->startSection('conteudo'); ?>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gerenciar Usuários</h3>
      <button class="btn btn-primary" data-usuario-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Usuário
      </button>
    </div>

    <div class="card-content">
      <div class="toolbar">
        <form method="GET" action="<?php echo e(route('usuarios.index')); ?>" class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="<?php echo e($busca); ?>" placeholder="Buscar usuários..." onchange="this.form.submit()">
        </form>
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr><th>Nome</th><th>E-mail</th><th>CPF</th><th>Perfil</th><th>CRF</th><th>Ações</th></tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><strong><?php echo e($usuario->nome); ?></strong></td>
                <td><?php echo e($usuario->email); ?></td>
                <td><?php echo e($usuario->cpf ?? '—'); ?></td>
                <td><span class="badge badge-primary"><?php echo e($usuario->perfilLabel()); ?></span></td>
                
                <td><?php echo e($usuario->perfil === 'farmaceutico' && $usuario->crf ? $usuario->crf : '—'); ?></td>
                <td class="table-actions">
                  <button class="btn btn-sm btn-outline"
                    data-usuario-editar
                    data-id="<?php echo e($usuario->id); ?>"
                    data-nome="<?php echo e($usuario->nome); ?>"
                    data-email="<?php echo e($usuario->email); ?>"
                    data-cpf="<?php echo e($usuario->cpf); ?>"
                    data-perfil="<?php echo e($usuario->perfil); ?>"
                    data-crf="<?php echo e($usuario->crf); ?>">Editar</button>
                  
                  <form method="POST" action="<?php echo e(route('usuarios.destroy', $usuario)); ?>" data-confirm="Tem certeza que deseja excluir este usuário?" style="display:inline;">
                    <?php echo csrf_field(); ?> 
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="6" class="empty-state">Nenhum usuário encontrado</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  <div class="modal-overlay" id="usuarioModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title" id="usuarioModalTitle">Novo Usuário</h3>
        <button class="modal-close" data-close-modal="usuarioModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="usuarioForm" method="POST" action="<?php echo e(route('usuarios.store')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" id="usuarioMethod" value="POST">
        
        <div class="modal-body">
          <div class="form-group">
            <label for="usuarioNome">Nome Completo</label>
            <input type="text" id="usuarioNome" name="nome" required>
          </div>
          <div class="form-group">
            <label for="usuarioEmail">E-mail</label>
            <input type="email" id="usuarioEmail" name="email" required>
          </div>
          <div class="form-group">
            <label for="usuarioCPF">CPF</label>
            <input type="text" id="usuarioCPF" name="cpf" placeholder="000.000.000-00" data-mask="cpf" maxlength="14" required>
          </div>
          <div class="form-group">
            <label for="usuarioPerfil">Perfil</label>
            <select id="usuarioPerfil" name="perfil" required>
              <option value="">Selecione...</option>
              <option value="admin">Administrador</option>
              <option value="farmaceutico">Farmacêutico</option>
              <option value="balconista">Balconista</option>
            </select>
          </div>
          
          
          <div class="form-group" id="crfField" style="display: none;">
            <label for="usuarioCRF">CRF</label>
            <input type="text" id="usuarioCRF" name="crf" placeholder="CRF-SP 00000">
          </div>
          
          <div class="form-group">
            <label for="usuarioSenha">Senha</label>
            <input type="password" id="usuarioSenha" name="password" placeholder="••••••••">
          </div>
          <div class="form-group">
            <label for="usuarioSenhaConf">Confirmar Senha</label>
            <input type="password" id="usuarioSenhaConf" name="password_confirmation" placeholder="••••••••">
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="usuarioModal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const usuarioForm = document.getElementById('usuarioForm');
  const usuarioBase = "<?php echo e(url('usuarios')); ?>";
  const perfilSelect = document.getElementById('usuarioPerfil');
  const crfField = document.getElementById('crfField');
  const crfInput = document.getElementById('usuarioCRF');

  // Controla dinamicamente a obrigatoriedade e visibilidade do CRF
  function atualizarCRF() {
    const ehFarma = perfilSelect.value === 'farmaceutico';
    crfField.style.display = ehFarma ? 'block' : 'none';
    crfInput.required = ehFarma;
  }

  perfilSelect.addEventListener('change', atualizarCRF);

  // Configuração para Novo Usuário (Senha obrigatória)
  document.querySelectorAll('[data-usuario-novo]').forEach(btn => btn.addEventListener('click', () => {
    usuarioForm.reset();
    usuarioForm.action = usuarioBase;
    document.getElementById('usuarioMethod').value = 'POST';
    document.getElementById('usuarioModalTitle').textContent = 'Novo Usuário';
    document.getElementById('usuarioSenha').required = true;
    document.getElementById('usuarioSenhaConf').required = true;
    document.getElementById('usuarioSenha').placeholder = '••••••••';
    document.getElementById('usuarioSenhaConf').placeholder = '••••••••';
    atualizarCRF();
    openModal('usuarioModal');
  }));

  // Configuração para Edição (Senha opcional)
  document.querySelectorAll('[data-usuario-editar]').forEach(btn => btn.addEventListener('click', () => {
    usuarioForm.reset();
    usuarioForm.action = usuarioBase + '/' + btn.dataset.id;
    document.getElementById('usuarioMethod').value = 'PUT';
    document.getElementById('usuarioModalTitle').textContent = 'Editar Usuário';
    document.getElementById('usuarioNome').value = btn.dataset.nome;
    document.getElementById('usuarioEmail').value = btn.dataset.email;
    document.getElementById('usuarioCPF').value = btn.dataset.cpf || '';
    perfilSelect.value = btn.dataset.perfil;
    crfInput.value = btn.dataset.crf || '';
    
    // Na edição, o operador pode deixar os campos de senha vazios caso queira manter a atual
    document.getElementById('usuarioSenha').required = false;
    document.getElementById('usuarioSenhaConf').required = false;
    document.getElementById('usuarioSenha').placeholder = 'Deixe em branco para manter';
    document.getElementById('usuarioSenhaConf').placeholder = 'Deixe em branco para manter';
    atualizarCRF();
    openModal('usuarioModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\usuarios\index.blade.php ENDPATH**/ ?>