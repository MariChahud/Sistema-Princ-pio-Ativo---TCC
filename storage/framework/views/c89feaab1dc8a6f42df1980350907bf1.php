

<?php $__env->startSection('titulo', 'Detalhes da Receita'); ?>

<?php $__env->startSection('conteudo'); ?>
  <a href="<?php echo e(route('receitas.index')); ?>" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Voltar para Receitas
  </a>

  <div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3 class="card-title">Ordem de Manipulação #<?php echo e(str_pad($receita->id, 3, '0', STR_PAD_LEFT)); ?></h3>
      <span class="badge <?php echo e($receita->statusBadgeClass()); ?>"><?php echo e($receita->statusLabel()); ?></span>
    </div>
    <div class="card-content">
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1.5rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border);">
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Fórmula</span>
          <strong style="font-size:1.1rem;color:var(--text);"><?php echo e($receita->nome_formula); ?></strong>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Cliente</span>
          <strong style="display:block;"><?php echo e($receita->cliente->nome ?? 'N/A'); ?></strong>
          <span style="font-size:0.85rem;color:var(--muted);">CPF: <?php echo e($receita->cliente->cpf ?? '—'); ?></span>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Prescritor</span>
          <strong style="display:block;"><?php echo e($receita->medico); ?></strong>
          <span style="font-size:0.85rem;color:var(--muted);">CRM: <?php echo e($receita->crm); ?></span>
        </div>
        <div>
          <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Data e Cápsulas</span>
          <strong style="display:block;"><?php echo e($receita->data->format('d/m/Y')); ?></strong>
          <span style="font-size:0.85rem;color:var(--muted);"><?php echo e($receita->qtd_capsulas); ?> Cápsulas</span>
        </div>
      </div>

      <h4 style="margin-bottom: 1rem;color:var(--primary-dark);">Composição / Insumos Separados</h4>
      <div class="table-container">
        <table>
          <thead>
            <tr><th>Insumo (Matéria-Prima)</th><th>Lote Utilizado</th><th>Dosagem p/ Cápsula</th><th>Quantidade Total Requerida</th></tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $receita->itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><strong><?php echo e($item->produto->nome ?? 'N/A'); ?></strong></td>
                <td><span class="badge badge-secondary"><?php echo e($item->lote->numero ?? 'N/A'); ?></span></td>
                <td><?php echo e(number_format($item->dosagem_mg, 2, ',', '.')); ?> mg</td>
                <td>
                  <strong><?php echo e(number_format(($item->dosagem_mg * $receita->qtd_capsulas) / 1000, 3, ',', '.')); ?> g</strong>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:space-between;align-items:center;background:var(--background-light);padding:1rem;border-radius:var(--radius);">
        <div>
          <span style="font-size:0.85rem;color:var(--muted);">Orçamento Final da Fórmula:</span>
          <strong style="font-size:1.25rem;color:#16a34a;margin-left:8px;">R$ <?php echo e(number_format($receita->orcamento, 2, ',', '.')); ?></strong>
        </div>
        
        <div style="display:flex;gap:0.5rem;">
          <?php if($receita->status === 'aguardando_pesagem'): ?>
            <a href="<?php echo e(route('receitas.pesagem', $receita)); ?>" class="btn btn-primary">Iniciar Processo de Pesagem</a>
          <?php endif; ?>
          <button onclick="window.print()" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Imprimir Ficha
          </button>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\receitas\show.blade.php ENDPATH**/ ?>