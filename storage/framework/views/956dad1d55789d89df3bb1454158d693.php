

<?php $__env->startSection('titulo', 'Emissão de Nota de Venda'); ?>

<?php $__env->startSection('conteudo'); ?>
  <div class="no-print" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="<?php echo e(route('financeiro.index')); ?>" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Voltar para o Caixa
    </a>
    <button onclick="window.print()" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
      Imprimir Recibo / Nota
    </button>
  </div>

  
  <div class="card" style="max-width: 600px; margin: 0 auto; border: 1px dashed var(--border); font-family: monospace;">
    <div class="card-content" style="padding: 2rem;">
      <div style="text-align: center; margin-bottom: 1.5rem; border-bottom: 1px dashed var(--border); padding-bottom: 1rem;">
        <h2 style="margin: 0 0 0.25rem 0; font-weight: 800; letter-spacing: 1px;">PRINCÍPIO ATIVO</h2>
        <p style="margin: 0; font-size: 0.85rem; color: var(--muted);">Farmácia de Manipulação e Magistral</p>
        <p style="margin: 0.25rem 0 0 0; font-size: 0.8rem;">CNPJ: 00.000.000/0001-00 — IE: Isento</p>
      </div>

      <div style="font-size: 0.85rem; margin-bottom: 1.5rem; line-height: 1.4;">
        <p style="margin: 0;"><strong>DOC. AUXILIAR DE VENDA:</strong> #<?php echo e(str_pad($transacao->id, 5, '0', STR_PAD_LEFT)); ?></p>
        <p style="margin: 0;"><strong>DATA EMISSÃO:</strong> <?php echo e(\Carbon\Carbon::parse($transacao->data)->format('d/m/Y H:i')); ?></p>
        <p style="margin: 0;"><strong>CLIENTE:</strong> <?php echo e($transacao->cliente->nome ?? 'Consumidor Geral'); ?></p>
        <p style="margin: 0;"><strong>CPF:</strong> <?php echo e($transacao->cliente->cpf ?? '—'); ?></p>
      </div>

      <div style="border-bottom: 1px dashed var(--border); padding-bottom: 0.5rem; margin-bottom: 1rem;">
        <h4 style="margin: 0 0 0.5rem 0; text-transform: uppercase; font-size: 0.9rem;">Descrição dos Itens Manipulados</h4>
        <?php if($transacao->receita): ?>
          <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 700;">
            <span>1x <?php echo e($transacao->receita->nome_formula); ?> (<?php echo e($transacao->receita->qtd_capsulas); ?> caps)</span>
            <span>R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?></span>
          </div>
          <div style="padding-left: 1rem; font-size: 0.8rem; color: var(--muted); line-height: 1.3;">
            <?php $__currentLoopData = $transacao->receita->itens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div>— <?php echo e($item->produto->nome); ?> (Lote: <?php echo e($item->lote->numero); ?>) - <?php echo e(number_format($item->dosagem_mg, 2, ',', '.')); ?>mg</div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php else: ?>
          <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
            <span><?php echo e($transacao->descricao); ?></span>
            <span>R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?></span>
          </div>
        <?php endif; ?>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; font-size: 1.15rem; font-weight: 800; margin-top: 1.5rem; padding-top: 0.5rem; border-top: 1px dashed var(--border);">
        <span>TOTAL RECEBIDO</span>
        <span>R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?></span>
      </div>

      <div style="font-size: 0.85rem; margin-top: 0.5rem; display: flex; justify-content: space-between; color: var(--muted);">
        <span>FORMA DE RECOLHIMENTO:</span>
        <span style="font-weight: 700;"><?php echo e(strtoupper($transacao->forma_pagamento)); ?></span>
      </div>

      <div style="text-align: center; margin-top: 3rem; font-size: 0.8rem; color: var(--muted); border-top: 1px solid var(--border); padding-top: 1rem;">
        <p style="margin: 0;">Obrigado pela preferência e confiança!</p>
        <p style="margin: 0.25rem 0 0 0;">Princípio Ativo — Qualidade e Precisão em Cada Dose.</p>
      </div>
    </div>
  </div>

  
  <style>
    @media print {
      body { background: #fff; color: #000; padding: 0; margin: 0; }
      .no-print, .sidebar, .header, header, nav, .back-btn { display: none !important; }
      .main-content, .content, main { padding: 0 !important; margin: 0 !important; }
      .card { border: none !important; box-shadow: none !important; max-width: 100% !important; }
    }
  </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Mariana\Documents\Faculdade\TCC\AtivoTCC\resources\views\financeiro\nota.blade.php ENDPATH**/ ?>