

<?php $__env->startSection('titulo', 'Clientes'); ?>

<?php $__env->startSection('conteudo'); ?>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Gerenciar Clientes</h3>
      <button class="btn btn-primary" data-cliente-novo>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Novo Cliente
      </button>
    </div>

    <div class="card-content">
      <div class="toolbar">
        
        <form method="GET" action="<?php echo e(route('clientes.index')); ?>" class="search-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="<?php echo e($busca); ?>" placeholder="Buscar clientes..." onchange="this.form.submit()">
        </form>
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>CPF</th><th>Ações</th></tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><strong><?php echo e($cliente->nome); ?></strong></td>
                <td><?php echo e($cliente->email); ?></td>
                <td><?php echo e($cliente->telefone); ?></td>
                <td><?php echo e($cliente->cpf); ?></td>
                <td class="table-actions">
                  
                  <button class="btn btn-sm btn-outline"
                    data-cliente-editar
                    data-id="<?php echo e($cliente->id); ?>"
                    data-nome="<?php echo e($cliente->nome); ?>"
                    data-email="<?php echo e($cliente->email); ?>"
                    data-telefone="<?php echo e($cliente->telefone); ?>"
                    data-cpf="<?php echo e($cliente->cpf); ?>">Editar</button>
                  
                  <form method="POST" action="<?php echo e(route('clientes.destroy', $cliente)); ?>" data-confirm="Tem certeza que deseja excluir este cliente?" style="display:inline;">
                    <?php echo csrf_field(); ?> 
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="5" class="empty-state">Nenhum cliente encontrado</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  
  <div class="modal-overlay" id="clienteModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title" id="clienteModalTitle">Novo Cliente</h3>
        <button class="modal-close" data-close-modal="clienteModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      
      <form id="clienteForm" method="POST" action="<?php echo e(route('clientes.store')); ?>">
        <?php echo csrf_field(); ?>
        
        <input type="hidden" name="_method" id="clienteMethod" value="POST">
        
        <div class="modal-body">
          <div class="form-group">
            <label for="clienteNome">Nome Completo</label>
            <input type="text" id="clienteNome" name="nome" required>
          </div>
          <div class="form-group">
            <label for="clienteEmail">E-mail</label>
            <input type="email" id="clienteEmail" name="email" required>
          </div>
          <div class="form-group">
            <label for="clienteTelefone">Telefone</label>
            <input type="tel" id="clienteTelefone" name="telefone" placeholder="(00) 00000-0000" data-mask="telefone" required>
          </div>
          <div class="form-group">
            <label for="clienteCPF">CPF</label>
            <input type="text" id="clienteCPF" name="cpf" placeholder="000.000.000-00" data-mask="cpf" required>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="clienteModal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // Liga os botões da tabela ao formulário modal (criação/edição) alterando a rota e os valores dinamicamente.
  const clienteForm = document.getElementById('clienteForm');
  const baseAction = "<?php echo e(url('clientes')); ?>";

  // Configura o modal para Modo de Cadastro
  document.querySelectorAll('[data-cliente-novo]').forEach(btn => btn.addEventListener('click', () => {
    clienteForm.reset();
    clienteForm.action = baseAction;
    document.getElementById('clienteMethod').value = 'POST';
    document.getElementById('clienteModalTitle').textContent = 'Novo Cliente';
    openModal('clienteModal');
  }));

  // Configura o modal para Modo de Edição
  document.querySelectorAll('[data-cliente-editar]').forEach(btn => btn.addEventListener('click', () => {
    clienteForm.reset();
    clienteForm.action = baseAction + '/' + btn.dataset.id;
    document.getElementById('clienteMethod').value = 'PUT'; // Altera o método para o Laravel entender a edição
    document.getElementById('clienteModalTitle').textContent = 'Editar Cliente';
    
    // Alimenta os inputs com os dados do cliente selecionado
    document.getElementById('clienteNome').value = btn.dataset.nome;
    document.getElementById('clienteEmail').value = btn.dataset.email;
    document.getElementById('clienteTelefone').value = btn.dataset.telefone;
    document.getElementById('clienteCPF').value = btn.dataset.cpf;
    openModal('clienteModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\clientes\index.blade.php ENDPATH**/ ?>