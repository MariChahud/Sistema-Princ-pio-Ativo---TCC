

<?php $__env->startSection('titulo', 'Receitas'); ?>

<?php $__env->startPush('estilos'); ?>
<style>
  .insumos-section { border:1px solid var(--border); border-radius:var(--radius); padding:1rem; margin-top:.5rem; background:var(--background-light); }
  .insumos-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; font-weight:600; font-size:.875rem; }
  .orcamento-box { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:.75rem 1rem; display:flex; justify-content:space-between; align-items:center; margin-top:1rem; }
  .orcamento-valor { font-size:1.125rem; font-weight:800; color:#16a34a; }
  #receitaModal .modal { max-width:600px; }
  #receitaModal .modal-body { max-height:72vh; overflow-y:auto; }
  .tabs-bar { display:flex; border-bottom:1px solid var(--border); margin-bottom:1.5rem; }
  .search-inline { display:flex; align-items:center; gap:.5rem; border:1px solid var(--border); border-radius:var(--radius); padding:.5rem .75rem; background:var(--card); }
  .search-inline input { border:none; background:transparent; outline:none; font-size:.875rem; width:200px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('conteudo'); ?>
  <div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
      <h3 class="card-title">Gerenciar Receitas</h3>
      <div style="display:flex;align-items:center;gap:.75rem;">
        <form method="GET" action="<?php echo e(route('receitas.index')); ?>" class="search-inline">
          <input type="hidden" name="status" value="<?php echo e($status); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="<?php echo e($busca); ?>" placeholder="Buscar receitas..." onchange="this.form.submit()">
        </form>
        <button class="btn btn-primary" data-receita-nova>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Nova Receita
        </button>
      </div>
    </div>
    
    <div class="card-content">
      
      <div class="tabs-bar">
        <?php $__currentLoopData = ['todos' => 'Todas', 'aguardando_pesagem' => 'Aguardando Pesagem', 'pesado' => 'Pesado', 'finalizado' => 'Finalizado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $rotulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a class="tab <?php echo e($status === $valor ? 'active' : ''); ?>" href="<?php echo e(route('receitas.index', ['status' => $valor, 'q' => $busca])); ?>"><?php echo e($rotulo); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      
      <div class="table-container">
        <table>
          <thead>
            <tr><th>#</th><th>Fórmula</th><th>Cliente</th><th>Médico</th><th>Data</th><th>Orçamento</th><th>Status</th><th>Ações</th></tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $receitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td>#<?php echo e(str_pad($receita->id, 3, '0', STR_PAD_LEFT)); ?></td>
                <td><a href="<?php echo e(route('receitas.show', $receita)); ?>" style="font-weight:700;color:var(--primary);text-decoration:none;"><?php echo e($receita->nome_formula); ?></a></td>
                <td><?php echo e($receita->cliente->nome ?? 'N/A'); ?></td>
                <td><?php echo e($receita->medico); ?></td>
                <td><?php echo e($receita->data->format('d/m/Y')); ?></td>
                <td>R$ <?php echo e(number_format($receita->orcamento, 2, ',', '.')); ?></td>
                <td><span class="badge <?php echo e($receita->statusBadgeClass()); ?>"><?php echo e($receita->statusLabel()); ?></span></td>
                <td class="table-actions">
                  <?php if($receita->status === 'aguardando_pesagem'): ?>
                    <a href="<?php echo e(route('receitas.pesagem', $receita)); ?>" class="btn btn-sm btn-outline">Pesar</a>
                  <?php endif; ?>
                  
                  
                  <button class="btn btn-sm btn-outline"
                    data-receita-editar
                    data-id="<?php echo e($receita->id); ?>"
                    data-payload='<?php echo json_encode([
                      "nome_formula" => $receita->nome_formula, "cliente_id" => $receita->cliente_id, "cliente_nome" => $receita->cliente->nome ?? "") ?>'
                    <?php if($receita->estaBloqueada()): ?> disabled title="Receita já foi pesada" style="opacity:.4;cursor:not-allowed;" <?php endif; ?>>Editar</button>
                  
                  <form method="POST" action="<?php echo e(route('receitas.destroy', $receita)); ?>" data-confirm="Confirmar exclusão desta receita?" style="display:inline;">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger" <?php if($receita->estaBloqueada()): ?> disabled style="opacity:.4;cursor:not-allowed;" <?php endif; ?>>Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td colspan="8" class="empty-state">Nenhuma receita encontrada</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modais'); ?>
  <div class="modal-overlay" id="receitaModal">
    <div class="modal" style="max-width:600px;">
      <div class="modal-header">
        <h3 class="modal-title" id="receitaModalTitle">Nova Receita</h3>
        <button class="modal-close" data-close-modal="receitaModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      
      <form id="receitaForm" method="POST" action="<?php echo e(route('receitas.store')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" id="receitaMethod" value="POST">
        <div class="modal-body" style="max-height:72vh;overflow-y:auto;">
          <div class="form-group">
            <label for="receitaNomeFormula">Nome da Fórmula</label>
            <input type="text" id="receitaNomeFormula" name="nome_formula" placeholder="Ex: Vitamina D3 + K2" required>
          </div>
          <div class="form-group">
            <label for="receitaCliente">Cliente</label>
            <select id="receitaCliente" name="cliente_id" required>
              <option value="">Selecione o cliente...</option>
              <?php $__currentLoopData = \App\Models\Cliente::orderBy('nome')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->nome); ?> — <?php echo e($cliente->cpf); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="grid-2">
            <div class="form-group">
              <label for="receitaMedico">Médico</label>
              <input type="text" id="receitaMedico" name="medico" placeholder="Dr(a). Nome" required>
            </div>
            <div class="form-group">
              <label for="receitaCRM">CRM</label>
              <input type="text" id="receitaCRM" name="crm" placeholder="CRM-SP 00000" required>
            </div>
          </div>
          <div class="grid-2">
            <div class="form-group">
              <label for="receitaData">Data</label>
              <input type="date" id="receitaData" name="data" required>
            </div>
            <div class="form-group">
              <label for="receitaQtdCapsulas">Qtd. Cápsulas</label>
              <input type="number" id="receitaQtdCapsulas" name="qtd_capsulas" min="1" placeholder="30" required>
            </div>
          </div>
          
          
          <div class="insumos-section">
            <div class="insumos-header">
              <span>Insumos da Fórmula</span>
              <button type="button" class="btn btn-outline btn-sm" data-add-insumo>+ Insumo</button>
            </div>
            <div id="insumosContainer"></div>
            
            <div class="orcamento-box">
              <span>Orçamento estimado (preview):</span>
              <span class="orcamento-valor" id="orcamentoValor">R$ 0,00</span>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-close-modal="receitaModal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="receitaBtnConfirmar">Confirmar</button>
        </div>
      </form>
    </div>
  </div>

  
  <script type="application/json" id="lotesDisponiveis">
    <?php echo json_encode($lotesDisponiveis->map(fn($l) => [
      'id' => $l->id, 'label' => ($l->produto->nome ?? 'N/A') . ' — ' . $l->numero, 'preco' => $l->produto->preco_base ?? 0) ?>
  </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const receitaForm = document.getElementById('receitaForm');
  const receitaBase = "<?php echo e(url('receitas')); ?>";
  const lotes = JSON.parse(document.getElementById('lotesDisponiveis').textContent);
  const container = document.getElementById('insumosContainer');
  let insumoIdx = 0;

  function opcoesLote(selecionado) {
    if (!lotes.length) return '<option value="">Nenhum lote disponível</option>';
    return lotes.map(l => `<option value="${l.id}" data-preco="${l.preco}" ${selecionado == l.id ? 'selected' : ''}>${l.label}</option>`).join('');
  }

  // Adiciona a linha de insumo dinamicamente com indexadores corretos para o request PHP
  function addInsumo(item) {
    const i = insumoIdx++;
    const div = document.createElement('div');
    div.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 36px;gap:8px;align-items:end;margin-bottom:12px;';
    div.dataset.insumo = i;
    div.innerHTML = `
      <div class="form-group" style="margin:0;">
        <label style="font-size:.75rem;">Lote / Produto</label>
        <select name="itens[${i}][lote_id]" class="insumo-lote" onchange="calcularOrcamento()" required>${opcoesLote(item?.lote_id)}</select>
      </div>
      <div class="form-group" style="margin:0;">
        <label style="font-size:.75rem;">Dosagem (mg)</label>
        <input type="number" name="itens[${i}][dosagem_mg]" class="insumo-dosagem" min="0.01" step="0.01" placeholder="500" value="${item?.dosagem_mg ?? ''}" oninput="calcularOrcamento()" required>
      </div>
      <button type="button" onclick="this.parentElement.remove();calcularOrcamento();" style="background:rgba(220,38,38,.1);border:none;border-radius:var(--radius);color:var(--destructive);cursor:pointer;height:36px;width:36px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>`;
    container.appendChild(div);
    calcularOrcamento();
  }

  function calcularOrcamento() {
    const qtd = parseFloat(document.getElementById('receitaQtdCapsulas').value) || 0;
    let total = 0;
    container.querySelectorAll('[data-insumo]').forEach(row => {
      const sel = row.querySelector('.insumo-lote');
      const dose = parseFloat(row.querySelector('.insumo-dosagem').value) || 0;
      const preco = parseFloat(sel.selectedOptions[0]?.dataset.preco) || 0;
      total += (dose / 1000) * preco * qtd;
    });
    document.getElementById('orcamentoValor').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
  }

  document.getElementById('receitaQtdCapsulas').addEventListener('input', calcularOrcamento);
  document.querySelectorAll('[data-add-insumo]').forEach(b => b.addEventListener('click', () => addInsumo()));

  // Reset e inicialização para Cadastro
  document.querySelectorAll('[data-receita-nova]').forEach(b => b.addEventListener('click', () => {
    receitaForm.reset();
    receitaForm.action = receitaBase;
    document.getElementById('receitaMethod').value = 'POST';
    document.getElementById('receitaModalTitle').textContent = 'Nova Receita';
    document.getElementById('receitaBtnConfirmar').textContent = 'Confirmar';
    container.innerHTML = ''; insumoIdx = 0;
    document.getElementById('receitaData').value = new Date().toISOString().split('T')[0];
    addInsumo();
    openModal('receitaModal');
  }));

  // Montagem e população para Edição
  document.querySelectorAll('[data-receita-editar]').forEach(b => b.addEventListener('click', () => {
    if (b.disabled) return;
    const data = JSON.parse(b.dataset.payload);
    receitaForm.reset();
    receitaForm.action = receitaBase + '/' + b.dataset.id;
    document.getElementById('receitaMethod').value = 'PUT';
    document.getElementById('receitaModalTitle').textContent = 'Editar Receita';
    document.getElementById('receitaBtnConfirmar').textContent = 'Atualizar';
    document.getElementById('receitaNomeFormula').value = data.nome_formula;
    document.getElementById('receitaCliente').value = data.cliente_id;
    document.getElementById('receitaMedico').value = data.medico;
    document.getElementById('receitaCRM').value = data.crm;
    document.getElementById('receitaData').value = data.data;
    document.getElementById('receitaQtdCapsulas').value = data.qtd_capsulas;
    container.innerHTML = ''; insumoIdx = 0;
    (data.itens || []).forEach(addInsumo);
    if (!data.itens || !data.itens.length) addInsumo();
    openModal('receitaModal');
  }));
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\receitas\index.blade.php ENDPATH**/ ?>