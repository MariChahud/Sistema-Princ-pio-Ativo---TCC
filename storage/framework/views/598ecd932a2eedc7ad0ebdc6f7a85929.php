
<div class="modal-overlay" id="produtoModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="produtoModalTitle">Novo Produto</h3>
      <button class="modal-close" data-close-modal="produtoModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="produtoForm" method="POST" action="<?php echo e(route('produtos.store')); ?>" novalidate>
      <?php echo csrf_field(); ?>
      <input type="hidden" name="_method"     id="produtoMethod" value="POST">
      <input type="hidden" name="_produto_id" id="produtoId"     value="">
      <div class="modal-body">
        <div class="form-group">
          <label for="produtoNome">Nome</label>
          <input type="text" id="produtoNome" name="nome" value="<?php echo e(old('nome')); ?>" required>
          <span class="field-error" id="erroNomeProduto"></span>
        </div>
        <div class="form-group">
          <label for="produtoDCB">DCB</label>
          <input type="text" id="produtoDCB" name="dcb" value="<?php echo e(old('dcb')); ?>" placeholder="00000" required>
          <span class="field-error" id="erroDCB"></span>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label>Unidade de Medida</label>
            <input type="hidden" name="unidade" id="produtoUnidade" value="g">
            <p style="padding:0.625rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.875rem;color:var(--muted);">Gramas (g)</p>
          </div>
          <div class="form-group">
            <label for="produtoPreco">Preço Base (R$/g)</label>
            <input type="number" id="produtoPreco" name="preco_base" value="<?php echo e(old('preco_base')); ?>" step="0.01" min="0" required>
            <span class="field-error" id="erroPreco"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="produtoEstoqueMin">Estoque Mínimo (g)</label>
          <input type="number" id="produtoEstoqueMin" name="estoque_minimo" value="<?php echo e(old('estoque_minimo')); ?>" min="0" required>
          <span class="field-error" id="erroEstoqueMin"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-close-modal="produtoModal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>


<div class="modal-overlay" id="loteModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Novo Lote</h3>
      <button class="modal-close" data-close-modal="loteModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="loteForm" method="POST" action="<?php echo e(route('lotes.store')); ?>" novalidate>
      <?php echo csrf_field(); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="loteProduto">Produto</label>
          <select id="loteProduto" name="produto_id" required>
            <?php if(isset($produtoFixo)): ?>
              <option value="<?php echo e($produtoFixo->id); ?>"><?php echo e($produtoFixo->nome); ?></option>
            <?php else: ?>
              <?php $__currentLoopData = ($produtos ?? \App\Models\Produto::orderBy('nome')->get()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>"><?php echo e($p->nome); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
          </select>
          <span class="field-error" id="erroProdutoLote"></span>
        </div>
        <div class="form-group">
          <label for="loteNumero">Número do Lote</label>
          <input type="text" id="loteNumero" name="numero" value="<?php echo e(old('numero')); ?>" placeholder="LOT-2026-000" required>
          <span class="field-error" id="erroNumeroLote"></span>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label for="loteQuantidade">Quantidade (g)</label>
            <input type="number" id="loteQuantidade" name="quantidade" value="<?php echo e(old('quantidade')); ?>" min="1" required>
            <span class="field-error" id="erroQuantidadeLote"></span>
          </div>
          <div class="form-group">
            <label for="loteValidade">Validade</label>
            <input type="date" id="loteValidade" name="validade" value="<?php echo e(old('validade')); ?>" required>
            <span class="field-error" id="erroValidadeLote"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="loteFornecedor">Fornecedor</label>
          <input type="text" id="loteFornecedor" name="fornecedor" value="<?php echo e(old('fornecedor')); ?>" required>
          <span class="field-error" id="erroFornecedorLote"></span>
        </div>
        <div class="form-group">
          <label for="loteCNPJ">CNPJ do Fornecedor</label>
          <input type="text" id="loteCNPJ" name="cnpj_fornecedor" value="<?php echo e(old('cnpj_fornecedor')); ?>" placeholder="00.000.000/0000-00" data-mask="cnpj">
          <span class="field-error" id="erroCNPJLote"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-close-modal="loteModal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script>

  // produto
  function limparErrosProduto() {
    [['produtoNome','erroNomeProduto'],['produtoDCB','erroDCB'],
     ['produtoPreco','erroPreco'],['produtoEstoqueMin','erroEstoqueMin']]
    .forEach(([i,e]) => limparErro(i, e));
  }

  [['produtoNome','erroNomeProduto'],['produtoDCB','erroDCB'],
   ['produtoPreco','erroPreco'],['produtoEstoqueMin','erroEstoqueMin']]
  .forEach(([inputId, erroId]) => {
    const el = document.getElementById(inputId);
    if (el) el.addEventListener('input', () => limparErro(inputId, erroId));
  });

  document.getElementById('produtoForm').addEventListener('submit', function(e) {
    limparErrosProduto();
    let ok = true;

    if (!document.getElementById('produtoNome').value.trim()) {
      mostrarErro('produtoNome', 'erroNomeProduto', 'O campo Nome é obrigatório.'); ok = false;
    }
    if (!document.getElementById('produtoDCB').value.trim()) {
      mostrarErro('produtoDCB', 'erroDCB', 'O campo DCB é obrigatório.'); ok = false;
    }
    if (document.getElementById('produtoPreco').value === '') {
      mostrarErro('produtoPreco', 'erroPreco', 'O campo Preço Base é obrigatório.'); ok = false;
    }
    if (document.getElementById('produtoEstoqueMin').value === '') {
      mostrarErro('produtoEstoqueMin', 'erroEstoqueMin', 'O campo Estoque Mínimo é obrigatório.'); ok = false;
    }

    if (!ok) e.preventDefault();
  });

  // lote
  function limparErrosLote() {
    [['loteProduto','erroProdutoLote'],['loteNumero','erroNumeroLote'],
     ['loteQuantidade','erroQuantidadeLote'],['loteValidade','erroValidadeLote'],
     ['loteFornecedor','erroFornecedorLote'],['loteCNPJ','erroCNPJLote']]
    .forEach(([i,e]) => limparErro(i, e));
  }

  [['loteProduto','erroProdutoLote'],['loteNumero','erroNumeroLote'],
   ['loteQuantidade','erroQuantidadeLote'],['loteValidade','erroValidadeLote'],
   ['loteFornecedor','erroFornecedorLote'],['loteCNPJ','erroCNPJLote']]
  .forEach(([inputId, erroId]) => {
    const el = document.getElementById(inputId);
    if (el) el.addEventListener('input', () => limparErro(inputId, erroId));
  });

  document.getElementById('loteForm').addEventListener('submit', function(e) {
    limparErrosLote();
    let ok = true;

    if (!document.getElementById('loteNumero').value.trim()) {
      mostrarErro('loteNumero', 'erroNumeroLote', 'O campo Número do Lote é obrigatório.'); ok = false;
    }
    if (!document.getElementById('loteQuantidade').value) {
      mostrarErro('loteQuantidade', 'erroQuantidadeLote', 'O campo Quantidade é obrigatório.'); ok = false;
    }
    if (!document.getElementById('loteValidade').value) {
      mostrarErro('loteValidade', 'erroValidadeLote', 'O campo Validade é obrigatório.'); ok = false;
    }
    if (!document.getElementById('loteFornecedor').value.trim()) {
      mostrarErro('loteFornecedor', 'erroFornecedorLote', 'O campo Fornecedor é obrigatório.'); ok = false;
    }

    const cnpj = document.getElementById('loteCNPJ').value.replace(/\D/g, '');
    if (!cnpj) {
      mostrarErro('loteCNPJ', 'erroCNPJLote', 'O campo CNPJ do Fornecedor é obrigatório.'); ok = false;
    } else if (cnpj.length !== 14) {
      mostrarErro('loteCNPJ', 'erroCNPJLote', 'Informe um CNPJ válido (14 dígitos).'); ok = false;
    }

    if (!ok) e.preventDefault();
  });

  //  Reabre modal com erro do Laravel 
  <?php if($errors->hasAny(['nome','dcb','preco_base','estoque_minimo'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
      <?php if(old('_method') === 'PUT' && old('_produto_id')): ?>
        document.getElementById('produtoForm').action = "<?php echo e(url('produtos')); ?>/<?php echo e(old('_produto_id')); ?>";
        document.getElementById('produtoMethod').value = 'PUT';
        document.getElementById('produtoId').value = '<?php echo e(old("_produto_id")); ?>';
      <?php endif; ?>
      openModal('produtoModal');
      <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('produtoNome', 'erroNomeProduto', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['dcb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('produtoDCB', 'erroDCB', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['preco_base'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('produtoPreco', 'erroPreco', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['estoque_minimo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('produtoEstoqueMin', 'erroEstoqueMin', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    });
  <?php endif; ?>

  <?php if($errors->hasAny(['produto_id','numero','quantidade','validade','fornecedor','cnpj_fornecedor'])): ?>
    document.addEventListener('DOMContentLoaded', () => {
      openModal('loteModal');
      <?php $__errorArgs = ['produto_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteProduto', 'erroProdutoLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteNumero', 'erroNumeroLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['quantidade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteQuantidade', 'erroQuantidadeLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['validade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteValidade', 'erroValidadeLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['fornecedor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteFornecedor', 'erroFornecedorLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      <?php $__errorArgs = ['cnpj_fornecedor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> mostrarErro('loteCNPJ', 'erroCNPJLote', '<?php echo e($message); ?>'); <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    });
  <?php endif; ?>
</script><?php /**PATH C:\Users\Mariana\Documents\GitHub\Sistema Princípio Ativo - TCC\resources\views/produtos/modais.blade.php ENDPATH**/ ?>