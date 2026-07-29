

<?php $__env->startSection('titulo', 'Usuários'); ?>

<?php $__env->startSection('conteudo'); ?>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gerenciar Usuários</h3>
      <button class="btn btn-primary" data-usuario-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Usuário
      </button>
    </div>

    <div class="card-content">
      <div class="toolbar">
        <form method="GET" action="<?php echo e(route('usuarios.index')); ?>" class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
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
                    data-crf="<?php echo e($usuario->crf ?? ''); ?>">Editar</button>

                  <form method="POST" action="<?php echo e(route('usuarios.destroy', $usuario)); ?>"
                    data-confirm="Tem certeza que deseja excluir este usuário?" style="display:inline;">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
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
  
  <div class="modal-overlay <?php echo e($errors->any() ? 'active' : ''); ?>" id="usuarioModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title" id="usuarioModalTitle">Novo Usuário</h3>
        <button class="modal-close" data-close-modal="usuarioModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <form id="usuarioForm" method="POST" action="<?php echo e(route('usuarios.store')); ?>" novalidate>
        <?php echo csrf_field(); ?>
        
        <input type="hidden" name="_method"      id="usuarioMethod" value="POST">
        <input type="hidden" name="_usuario_id"  id="usuarioId"     value="">

        <div class="modal-body">

          <div class="form-group">
            <label for="usuarioNome">Nome Completo</label>
            <input type="text" id="usuarioNome" name="nome"
              value="<?php echo e(old('nome')); ?>"
              class="<?php echo e($errors->has('nome') ? 'input-erro' : ''); ?>">
            <span class="field-error <?php echo e($errors->has('nome') ? 'visivel' : ''); ?>" id="erroNome">
              <?php echo e($errors->first('nome') ?: 'O campo Nome é obrigatório.'); ?>

            </span>
          </div>

          <div class="form-group">
            <label for="usuarioEmail">E-mail</label>
            <input type="email" id="usuarioEmail" name="email"
              value="<?php echo e(old('email')); ?>"
              class="<?php echo e($errors->has('email') ? 'input-erro' : ''); ?>">
            <span class="field-error <?php echo e($errors->has('email') ? 'visivel' : ''); ?>" id="erroEmail">
              <?php echo e($errors->first('email') ?: 'Informe um e-mail válido.'); ?>

            </span>
          </div>

          <div class="form-group">
            <label for="usuarioCPF">CPF</label>
            <input type="text" id="usuarioCPF" name="cpf"
              value="<?php echo e(old('cpf')); ?>"
              placeholder="000.000.000-00" data-mask="cpf" maxlength="14"
              class="<?php echo e($errors->has('cpf') ? 'input-erro' : ''); ?>">
            <span class="field-error <?php echo e($errors->has('cpf') ? 'visivel' : ''); ?>" id="erroCPF">
              <?php echo e($errors->first('cpf') ?: 'O campo CPF é obrigatório.'); ?>

            </span>
          </div>

          <div class="form-group">
            <label for="usuarioPerfil">Perfil</label>
            <select id="usuarioPerfil" name="perfil"
              class="<?php echo e($errors->has('perfil') ? 'input-erro' : ''); ?>">
              <option value="">Selecione...</option>
              <option value="admin"        <?php echo e(old('perfil') === 'admin'        ? 'selected' : ''); ?>>Administrador</option>
              <option value="farmaceutico" <?php echo e(old('perfil') === 'farmaceutico' ? 'selected' : ''); ?>>Farmacêutico</option>
              <option value="balconista"   <?php echo e(old('perfil') === 'balconista'   ? 'selected' : ''); ?>>Balconista</option>
            </select>
            <span class="field-error <?php echo e($errors->has('perfil') ? 'visivel' : ''); ?>" id="erroPerfil">
              <?php echo e($errors->first('perfil') ?: 'Selecione um perfil.'); ?>

            </span>
          </div>

          <div class="form-group" id="crfField"
            style="<?php echo e(old('perfil') === 'farmaceutico' || $errors->has('crf') ? 'display:block' : 'display:none'); ?>">
            <label for="usuarioCRF">CRF</label>
            <input type="text" id="usuarioCRF" name="crf"
              value="<?php echo e(old('crf')); ?>"
              placeholder="CRF-SP 00000"
              class="<?php echo e($errors->has('crf') ? 'input-erro' : ''); ?>">
            <span class="field-error <?php echo e($errors->has('crf') ? 'visivel' : ''); ?>" id="erroCRF">
              <?php echo e($errors->first('crf') ?: 'O campo CRF é obrigatório para Farmacêutico.'); ?>

            </span>
          </div>

          <div class="form-group">
            <label for="usuarioSenha">
              Senha
              <span id="senhaHint" style="font-size:0.75rem;color:var(--muted);font-weight:400;">
                (deixe em branco para manter a atual)
              </span>
            </label>
            <input type="password" id="usuarioSenha" name="password" placeholder="••••••••"
              class="<?php echo e($errors->has('password') ? 'input-erro' : ''); ?>">
            <span class="field-error <?php echo e($errors->has('password') ? 'visivel' : ''); ?>" id="erroSenha">
              <?php echo e($errors->first('password') ?: 'A senha deve ter no mínimo 6 caracteres.'); ?>

            </span>
          </div>

          <div class="form-group">
            <label for="usuarioSenhaConf">Confirmar Senha</label>
            <input type="password" id="usuarioSenhaConf" name="password_confirmation" placeholder="••••••••">
            <span class="field-error" id="erroSenhaConf">A confirmação de senha não confere.</span>
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
  const usuarioForm  = document.getElementById('usuarioForm');
  const usuarioBase  = "<?php echo e(url('usuarios')); ?>";
  const perfilSelect = document.getElementById('usuarioPerfil');
  const crfField     = document.getElementById('crfField');
  const crfInput     = document.getElementById('usuarioCRF');
  const senhaHint    = document.getElementById('senhaHint');

  // Detecta se o modal está aberto por erro do Laravel e se era edição
  const temErros      = <?php echo e($errors->any() ? 'true' : 'false'); ?>;
  const erroEhEdicao  = <?php echo e($errors->any() && old('_method') === 'PUT' ? 'true' : 'false'); ?>;
  const erroUsuarioId = "<?php echo e(old('_usuario_id')); ?>";
  let   modoEdicao    = false;

  // Se voltou com erro de edição, restaura a action e o modo
  if (temErros && erroEhEdicao && erroUsuarioId) {
    modoEdicao = true;
    usuarioForm.action = usuarioBase + '/' + erroUsuarioId;
    document.getElementById('usuarioMethod').value = 'PUT';
    document.getElementById('usuarioId').value     = erroUsuarioId;
    document.getElementById('usuarioModalTitle').textContent = 'Editar Usuário';
    senhaHint.style.display = 'inline';
  }

  // ── CRF ────────────────────────────────────────────────────────────
  function atualizarCRF() {
    const ehFarma = perfilSelect.value === 'farmaceutico';
    crfField.style.display = ehFarma ? 'block' : 'none';
  }
  perfilSelect.addEventListener('change', atualizarCRF);

  // mostrarErro() e limparErro() agora são globais, definidas em public/js/app.js
  function limparTodos() {
    [['usuarioNome','erroNome'],['usuarioEmail','erroEmail'],['usuarioCPF','erroCPF'],
     ['usuarioPerfil','erroPerfil'],['usuarioCRF','erroCRF'],
     ['usuarioSenha','erroSenha'],['usuarioSenhaConf','erroSenhaConf']]
    .forEach(([i,e]) => limparErro(i, e));
  }

  // Limpa erro ao digitar
  [['usuarioNome','erroNome'],['usuarioEmail','erroEmail'],['usuarioCPF','erroCPF'],
   ['usuarioPerfil','erroPerfil'],['usuarioCRF','erroCRF'],
   ['usuarioSenha','erroSenha'],['usuarioSenhaConf','erroSenhaConf']]
  .forEach(([inputId, erroId]) => {
    const el = document.getElementById(inputId);
    if (el) el.addEventListener('input', () => limparErro(inputId, erroId));
  });

  // ── Validação JS antes de enviar ───────────────────────────────────
  usuarioForm.addEventListener('submit', function(e) {
    limparTodos();
    let ok = true;

    if (!document.getElementById('usuarioNome').value.trim()) {
      mostrarErro('usuarioNome', 'erroNome', 'O campo Nome é obrigatório.'); ok = false;
    }

    const email = document.getElementById('usuarioEmail').value.trim();
    if (!email) {
      mostrarErro('usuarioEmail', 'erroEmail', 'O campo E-mail é obrigatório.'); ok = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      mostrarErro('usuarioEmail', 'erroEmail', 'Informe um e-mail válido.'); ok = false;
    }

    if (!document.getElementById('usuarioCPF').value.trim()) {
      mostrarErro('usuarioCPF', 'erroCPF', 'O campo CPF é obrigatório.'); ok = false;
    }

    if (!perfilSelect.value) {
      mostrarErro('usuarioPerfil', 'erroPerfil', 'Selecione um perfil para o usuário.'); ok = false;
    }

    if (perfilSelect.value === 'farmaceutico' && !crfInput.value.trim()) {
      mostrarErro('usuarioCRF', 'erroCRF', 'O campo CRF é obrigatório para Farmacêutico.'); ok = false;
    }

    const senha = document.getElementById('usuarioSenha').value;
    const conf  = document.getElementById('usuarioSenhaConf').value;

    // Senha obrigatória só no cadastro; na edição pode ficar em branco
    if (!modoEdicao && !senha) {
      mostrarErro('usuarioSenha', 'erroSenha', 'O campo Senha é obrigatório.'); ok = false;
    } else if (senha && senha.length < 6) {
      mostrarErro('usuarioSenha', 'erroSenha', 'A senha deve ter no mínimo 6 caracteres.'); ok = false;
    } else if (senha && senha !== conf) {
      mostrarErro('usuarioSenhaConf', 'erroSenhaConf', 'A confirmação de senha não confere.'); ok = false;
    }

    if (!ok) e.preventDefault();
  });

  // ── Abrir: Novo Usuário ────────────────────────────────────────────
  document.querySelectorAll('[data-usuario-novo]').forEach(btn => btn.addEventListener('click', () => {
    modoEdicao = false;
    usuarioForm.reset();
    limparTodos();
    usuarioForm.action = usuarioBase;
    document.getElementById('usuarioMethod').value = 'POST';
    document.getElementById('usuarioId').value     = '';
    document.getElementById('usuarioModalTitle').textContent = 'Novo Usuário';
    senhaHint.style.display = 'none';
    atualizarCRF();
    openModal('usuarioModal');
  }));

  // ── Abrir: Editar Usuário ──────────────────────────────────────────
  document.querySelectorAll('[data-usuario-editar]').forEach(btn => btn.addEventListener('click', () => {
    modoEdicao = true;
    usuarioForm.reset();
    limparTodos();
    usuarioForm.action = usuarioBase + '/' + btn.dataset.id;
    document.getElementById('usuarioMethod').value = 'PUT';
    document.getElementById('usuarioId').value     = btn.dataset.id;
    document.getElementById('usuarioModalTitle').textContent = 'Editar Usuário';
    document.getElementById('usuarioNome').value   = btn.dataset.nome;
    document.getElementById('usuarioEmail').value  = btn.dataset.email;
    document.getElementById('usuarioCPF').value    = btn.dataset.cpf || '';
    perfilSelect.value = btn.dataset.perfil;
    crfInput.value     = btn.dataset.crf || '';
    senhaHint.style.display = 'inline';
    atualizarCRF();
    openModal('usuarioModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views/usuarios/index.blade.php ENDPATH**/ ?>