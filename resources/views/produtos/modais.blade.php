{{-- Modal Produto --}}
<div class="modal-overlay" id="produtoModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="produtoModalTitle">Novo Produto</h3>
      <button class="modal-close" data-close-modal="produtoModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="produtoForm" method="POST" action="{{ route('produtos.store') }}" novalidate>
      @csrf
      <input type="hidden" name="_method"     id="produtoMethod" value="POST">
      <input type="hidden" name="_produto_id" id="produtoId"     value="">
      <div class="modal-body">
        <div class="form-group">
          <label for="produtoNome">Nome</label>
          <input type="text" id="produtoNome" name="nome" value="{{ old('nome') }}" required>
          <span class="field-error" id="erroNomeProduto"></span>
        </div>
        <div class="form-group">
          <label for="produtoDCB">DCB</label>
          <input type="text" id="produtoDCB" name="dcb" value="{{ old('dcb') }}" placeholder="00000" required>
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
            <input type="number" id="produtoPreco" name="preco_base" value="{{ old('preco_base') }}" step="0.01" min="0" required>
            <span class="field-error" id="erroPreco"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="produtoEstoqueMin">Estoque Mínimo (g)</label>
          <input type="number" id="produtoEstoqueMin" name="estoque_minimo" value="{{ old('estoque_minimo') }}" min="0" required>
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

{{-- Modal Lote --}}
<div class="modal-overlay" id="loteModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Novo Lote</h3>
      <button class="modal-close" data-close-modal="loteModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="loteForm" method="POST" action="{{ route('lotes.store') }}" novalidate>
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label for="loteProduto">Produto</label>
          <select id="loteProduto" name="produto_id" required>
            @if (isset($produtoFixo))
              <option value="{{ $produtoFixo->id }}">{{ $produtoFixo->nome }}</option>
            @else
              @foreach (($produtos ?? \App\Models\Produto::orderBy('nome')->get()) as $p)
                <option value="{{ $p->id }}">{{ $p->nome }}</option>
              @endforeach
            @endif
          </select>
          <span class="field-error" id="erroProdutoLote"></span>
        </div>
        <div class="form-group">
          <label for="loteNumero">Número do Lote</label>
          <input type="text" id="loteNumero" name="numero" value="{{ old('numero') }}" placeholder="LOT-2026-000" required>
          <span class="field-error" id="erroNumeroLote"></span>
        </div>
        <div class="grid-2">
          <div class="form-group">
            <label for="loteQuantidade">Quantidade (g)</label>
            <input type="number" id="loteQuantidade" name="quantidade" value="{{ old('quantidade') }}" min="1" required>
            <span class="field-error" id="erroQuantidadeLote"></span>
          </div>
          <div class="form-group">
            <label for="loteValidade">Validade</label>
            <input type="date" id="loteValidade" name="validade" value="{{ old('validade') }}" required>
            <span class="field-error" id="erroValidadeLote"></span>
          </div>
        </div>
        <div class="form-group">
          <label for="loteFornecedor">Fornecedor</label>
          <input type="text" id="loteFornecedor" name="fornecedor" value="{{ old('fornecedor') }}" required>
          <span class="field-error" id="erroFornecedorLote"></span>
        </div>
        <div class="form-group">
          <label for="loteCNPJ">CNPJ do Fornecedor</label>
          <input type="text" id="loteCNPJ" name="cnpj_fornecedor" value="{{ old('cnpj_fornecedor') }}" placeholder="00.000.000/0000-00" data-mask="cnpj">
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
  @if ($errors->hasAny(['nome','dcb','preco_base','estoque_minimo']))
    document.addEventListener('DOMContentLoaded', () => {
      @if (old('_method') === 'PUT' && old('_produto_id'))
        document.getElementById('produtoForm').action = "{{ url('produtos') }}/{{ old('_produto_id') }}";
        document.getElementById('produtoMethod').value = 'PUT';
        document.getElementById('produtoId').value = '{{ old("_produto_id") }}';
      @endif
      openModal('produtoModal');
      @error('nome') mostrarErro('produtoNome', 'erroNomeProduto', '{{ $message }}'); @enderror
      @error('dcb') mostrarErro('produtoDCB', 'erroDCB', '{{ $message }}'); @enderror
      @error('preco_base') mostrarErro('produtoPreco', 'erroPreco', '{{ $message }}'); @enderror
      @error('estoque_minimo') mostrarErro('produtoEstoqueMin', 'erroEstoqueMin', '{{ $message }}'); @enderror
    });
  @endif

  @if ($errors->hasAny(['produto_id','numero','quantidade','validade','fornecedor','cnpj_fornecedor']))
    document.addEventListener('DOMContentLoaded', () => {
      openModal('loteModal');
      @error('produto_id') mostrarErro('loteProduto', 'erroProdutoLote', '{{ $message }}'); @enderror
      @error('numero') mostrarErro('loteNumero', 'erroNumeroLote', '{{ $message }}'); @enderror
      @error('quantidade') mostrarErro('loteQuantidade', 'erroQuantidadeLote', '{{ $message }}'); @enderror
      @error('validade') mostrarErro('loteValidade', 'erroValidadeLote', '{{ $message }}'); @enderror
      @error('fornecedor') mostrarErro('loteFornecedor', 'erroFornecedorLote', '{{ $message }}'); @enderror
      @error('cnpj_fornecedor') mostrarErro('loteCNPJ', 'erroCNPJLote', '{{ $message }}'); @enderror
    });
  @endif
</script>