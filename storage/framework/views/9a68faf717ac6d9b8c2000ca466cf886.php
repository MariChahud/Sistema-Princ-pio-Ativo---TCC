
<div class="modal-overlay" id="produtoModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="produtoModalTitle">Novo Produto</h3>
      <button class="modal-close" data-close-modal="produtoModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="produtoForm" method="POST" action="<?php echo e(route('produtos.store')); ?>">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="_method" id="produtoMethod" value="POST">
      <div class="modal-body">
        <div class="form-group">
          <label for="produtoNome">Nome</label>
          <input type="text" id="produtoNome" name="nome" required>
        </div>
        <div class="form-group">
          <label for="produtoDCB">DCB</label>
          <input type="text" id="produtoDCB" name="dcb" placeholder="00000" required>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label for="produtoUnidade">Unidade</label>
            <select id="produtoUnidade" name="unidade" required>
              <option value="">Selecione...</option>
              <option value="g">Gramas (g)</option>
              <option value="mg">Miligramas (mg)</option>
              <option value="ml">Mililitros (ml)</option>
              <option value="un">Unidades (un)</option>
            </select>
          </div>
          <div class="form-group">
            <label for="produtoPreco">Preço Base (R$)</label>
            <input type="number" id="produtoPreco" name="preco_base" step="0.01" min="0" required>
          </div>
        </div>
        <div class="form-group">
          <label for="produtoEstoqueMin">Estoque Mínimo</label>
          <input type="number" id="produtoEstoqueMin" name="estoque_minimo" min="0" required>
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
    <form id="loteForm" method="POST" action="<?php echo e(route('lotes.store')); ?>">
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
        </div>
        <div class="form-group">
          <label for="loteNumero">Número do Lote</label>
          <input type="text" id="loteNumero" name="numero" placeholder="LOT-2026-000" required>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label for="loteQuantidade">Quantidade</label>
            <input type="number" id="loteQuantidade" name="quantidade" min="1" required>
          </div>
          <div class="form-group">
            <label for="loteValidade">Validade</label>
            <input type="date" id="loteValidade" name="validade" required>
          </div>
        </div>
        <div class="form-group">
          <label for="loteFornecedor">Fornecedor</label>
          <input type="text" id="loteFornecedor" name="fornecedor" required>
        </div>
        <div class="form-group">
          <label for="loteCNPJ">CNPJ do Fornecedor</label>
          <input type="text" id="loteCNPJ" name="cnpj_fornecedor" placeholder="00.000.000/0000-00" data-mask="cnpj">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-close-modal="loteModal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\produtos\modais.blade.php ENDPATH**/ ?>