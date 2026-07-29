// ================================
// Princípio Ativo — JS global do layout
// Define as funções usadas por todas as views Blade:
//   - openModal(id) / closeModal(id)
//   - [data-close-modal] -> fecha modal
//   - [data-confirm]     -> confirma antes de enviar form (excluir, desativar, etc.)
//   - [data-toggle-sidebar] -> abre/fecha sidebar no mobile
//   - [data-tab]         -> alterna abas (ex: Produtos / Lotes)
//   - clique fora do modal (.modal-overlay) fecha o modal
//   - tecla ESC fecha o modal aberto
// ================================

function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.add('active');
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.classList.remove('active');
}

// ── Helpers de validação de formulário (usados em Usuários, Clientes,
//    Produtos/Lotes e Receitas para exibir/limpar mensagens de erro
//    abaixo dos campos) ────────────────────────────────────────────
function mostrarErro(inputId, erroId, msg) {
  const el = document.getElementById(inputId);
  const er = document.getElementById(erroId);
  if (el) el.classList.add('input-erro');
  if (er) { er.textContent = msg; er.classList.add('visivel'); }
}

function limparErro(inputId, erroId) {
  const el = document.getElementById(inputId);
  const er = document.getElementById(erroId);
  if (el) el.classList.remove('input-erro');
  if (er) er.classList.remove('visivel');
}

document.addEventListener('DOMContentLoaded', () => {

  // ── Fechar modal pelo botão (X / Cancelar) ──────────────────────
  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      closeModal(btn.getAttribute('data-close-modal'));
    });
  });

  // ── Fechar modal clicando fora (no overlay) ─────────────────────
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.classList.remove('active');
    });
  });

  // ── Fechar modal ativo com ESC ───────────────────────────────────
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
  });

  // ── Confirmação antes de enviar formulários (excluir, desativar...) ─
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', (e) => {
      const msg = form.getAttribute('data-confirm') || 'Tem certeza?';
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // ── Sidebar mobile (abrir/fechar) ────────────────────────────────
  document.querySelectorAll('[data-toggle-sidebar]').forEach(btn => {
    btn.addEventListener('click', () => {
      const sidebar = document.querySelector('.sidebar');
      if (sidebar) sidebar.classList.toggle('active');
    });
  });

  // ── Alternância de abas genérica ([data-tab] + .tab-content) ────
  document.querySelectorAll('[data-tab]').forEach(tabBtn => {
    tabBtn.addEventListener('click', () => {
      document.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
      tabBtn.classList.add('active');
      const target = document.getElementById(tabBtn.dataset.tab);
      if (target) target.style.display = 'block';
    });
  });

  // ── Máscaras simples (CPF / CNPJ) para campos com data-mask ──────
  document.querySelectorAll('input[data-mask="cpf"]').forEach(input => {
    input.addEventListener('input', () => {
      let v = input.value.replace(/\D/g, '').slice(0, 11);
      v = v.replace(/(\d{3})(\d)/, '$1.$2')
           .replace(/(\d{3})(\d)/, '$1.$2')
           .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      input.value = v;
    });
  });

  document.querySelectorAll('input[data-mask="cnpj"]').forEach(input => {
    input.addEventListener('input', () => {
      let v = input.value.replace(/\D/g, '').slice(0, 14);
      v = v.replace(/^(\d{2})(\d)/, '$1.$2')
           .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
           .replace(/\.(\d{3})(\d)/, '.$1/$2')
           .replace(/(\d{4})(\d)/, '$1-$2');
      input.value = v;
    });
  });

  // ── Fecha automaticamente alertas de sucesso/erro após alguns segundos ─
  document.querySelectorAll('.alert-success').forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.4s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 400);
    }, 4000);
  });

});