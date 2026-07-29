@extends('layouts.app')

@section('titulo', 'Cabine de Pesagem Laboratorial')

@section('conteudo')
  <a href="{{ route('receitas.index') }}" class="back-btn" style="display:inline-flex;align-items:center;gap:6px;color:var(--primary);font-weight:600;text-decoration:none;margin-bottom:1rem;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Cancelar e Sair do Laboratório
  </a>

  <div class="card">
    <div class="card-header" style="background:#0f172a;color:#fff;">
      <div>
        <h3 class="card-title" style="color:#fff;">Processo de Fracionamento de Insumos</h3>
        <p style="font-size:0.8rem;color:#94a3b8;margin:0;">Fórmula: {{ $receita->nome_formula }} | Ordem: #{{ str_pad($receita->id, 3, '0', STR_PAD_LEFT) }}</p>
      </div>
      <span class="badge badge-warning">Modo Balança Ativo</span>
    </div>

    <form method="POST" action="{{ route('receitas.pesagem.confirmar', $receita) }}">
      @csrf
      <div class="card-content" style="padding-top:1.5rem;">
        <blockquote style="margin:0 0 1.5rem 0;border-left:4px solid var(--primary);padding:0.5rem 1rem;background:var(--background-light);font-size:0.9rem;">
          <strong>Instruções de Pesagem:</strong> Certifique-se de calibrar a balança analítica antes de registrar o peso. O valor digitado deve ser <strong>exatamente igual</strong> à massa teórica calculada para cada insumo.
        </blockquote>

        @foreach ($receita->itens as $item)
          @php $pesoAlvo = round(($item->dosagem_mg * $receita->qtd_capsulas) / 1000, 4); @endphp
          <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;display:grid;grid-template-columns:2fr 1fr 1fr;gap:1.5rem;align-items:center;">
            <div>
              <span style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:700;">Componente Ativo / Lote</span>
              <strong style="display:block;font-size:1.05rem;">{{ $item->produto->nome ?? 'N/A' }}</strong>
              <span style="font-size:0.85rem;color:var(--primary);">Lote: {{ $item->lote->numero ?? 'N/A' }} (Disponível: {{ rtrim(rtrim(number_format($item->lote->quantidade ?? 0, 4, '.', ''), '0'), '.') }}g)</span>
            </div>
            <div style="text-align:right;">
              <span style="display:block;font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:600;">Massa Teórica</span>
              <strong style="font-size:1.2rem;color:var(--text-dark);">{{ number_format($pesoAlvo, 4, ',', '.') }} g</strong>
            </div>
            <div class="form-group" style="margin:0;">
              <label style="font-size:0.75rem;font-weight:700;color:var(--primary-dark);">Massa Confirmada (g)</label>
              <input type="number" name="peso_real[{{ $item->id }}]"
                value="{{ $pesoAlvo }}"
                step="0.0001" min="0.0001"
                style="font-weight:700;font-size:1.1rem;text-align:center;border-color:var(--primary);"
                required>
            </div>
          </div>
        @endforeach
      </div>

      <div class="modal-footer" style="background:var(--background-light);border-top:1px solid var(--border);padding:1rem 1.5rem;">
        <span style="font-size:0.85rem;color:var(--muted);">
          Ao confirmar, a receita mudará para o status <strong>Pesado</strong> e o estoque sofrerá o decréscimo proporcional.
          O peso informado deve ser <strong>exatamente igual</strong> ao peso teórico.
        </span>
        <button type="submit" class="btn btn-primary" style="background:#16a34a;border-color:#16a34a;">
          Finalizar e Confirmar Pesagem
        </button>
      </div>
    </form>
  </div>
@endsection