<?php
namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Produto;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReceitaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'todos');
        $busca  = $request->query('q');
        $receitas = Receita::query()
            ->with(['cliente', 'itens.produto', 'itens.lote'])
            ->when($status !== 'todos', fn ($query) => $query->where('status', $status))
            ->when($busca, function ($query, $busca) {
                $query->where('nome_formula', 'like', "%{$busca}%")
                    ->orWhere('medico', 'like', "%{$busca}%")
                    ->orWhereHas('cliente', fn ($q) => $q->where('nome', 'like', "%{$busca}%"));
            })
            ->orderByDesc('data')
            ->get();
        $lotesDisponiveis = Lote::with('produto')
            ->where('ativo', true)
            ->whereDate('validade', '>', now())
            ->get();
        return view('receitas.index', compact('receitas', 'lotesDisponiveis', 'status', 'busca'));
    }

    public function show(Receita $receita)
    {
        $receita->load(['cliente', 'itens.produto', 'itens.lote']);
        return view('receitas.show', compact('receita'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        DB::transaction(function () use ($dados) {
            $receita = Receita::create([
                'cliente_id'   => $dados['cliente_id'],
                'nome_formula' => $dados['nome_formula'],
                'medico'       => $dados['medico'],
                'crm'          => $dados['crm'],
                'data'         => $dados['data'],
                'qtd_capsulas' => $dados['qtd_capsulas'],
                'status'       => 'aguardando_pesagem',
                'orcamento'    => $this->calcularOrcamento($dados['itens'], $dados['qtd_capsulas']),
            ]);
            foreach ($dados['itens'] as $item) {
                $lote = Lote::findOrFail($item['lote_id']);
                $receita->itens()->create([
                    'produto_id' => $lote->produto_id,
                    'lote_id'    => $lote->id,
                    'dosagem_mg' => $item['dosagem_mg'],
                    'peso_real'  => null,
                ]);
            }
        });
        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita cadastrada com sucesso.');
    }

    public function update(Request $request, Receita $receita)
    {
        if ($receita->estaBloqueada()) {
            return back()->with('erro', 'Não é possível editar uma receita que já foi pesada.');
        }
        $dados = $this->validar($request);
        DB::transaction(function () use ($dados, $receita) {
            $receita->update([
                'cliente_id'   => $dados['cliente_id'],
                'nome_formula' => $dados['nome_formula'],
                'medico'       => $dados['medico'],
                'crm'          => $dados['crm'],
                'data'         => $dados['data'],
                'qtd_capsulas' => $dados['qtd_capsulas'],
                'orcamento'    => $this->calcularOrcamento($dados['itens'], $dados['qtd_capsulas']),
            ]);
            $receita->itens()->delete();
            foreach ($dados['itens'] as $item) {
                $lote = Lote::findOrFail($item['lote_id']);
                $receita->itens()->create([
                    'produto_id' => $lote->produto_id,
                    'lote_id'    => $lote->id,
                    'dosagem_mg' => $item['dosagem_mg'],
                    'peso_real'  => null,
                ]);
            }
        });
        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita atualizada com sucesso.');
    }

    public function destroy(Receita $receita)
    {
        if ($receita->estaBloqueada()) {
            return back()->with('erro', 'Não é possível excluir uma receita que já iniciou a manipulação ou venda.');
        }
        $receita->delete();
        return redirect()->route('receitas.index')
            ->with('sucesso', 'Receita excluída com sucesso.');
    }

    public function pesagem(Receita $receita)
    {
        if ($receita->status !== 'aguardando_pesagem') {
            return redirect()->route('receitas.index')
                ->with('erro', 'Esta receita não está aguardando pesagem.');
        }
        $receita->load(['cliente', 'itens.produto', 'itens.lote']);
        return view('receitas.pesagem', compact('receita'));
    }

    public function confirmarPesagem(Request $request, Receita $receita)
    {
        $request->validate([
            'peso_real'   => ['required', 'array'],
            'peso_real.*' => ['required', 'numeric', 'min:0.0001'],
        ]);
        $pesos = $request->input('peso_real');

        // Verifica se o peso real é EXATAMENTE igual ao peso teórico
        foreach ($receita->itens as $item) {
            $real    = round((float) ($pesos[$item->id] ?? 0), 4);
            $teorico = round($item->pesoTeorico(), 4);

            if ($real !== $teorico) {
                return back()->with('erro', sprintf(
                    'Divergência de pesagem no insumo "%s": peso informado (%.4fg) é diferente do peso teórico (%.4fg). Corrija antes de confirmar.',
                    $item->produto->nome ?? '—',
                    $real,
                    $teorico
                ))->withInput();
            }
        }

        // Baixa no estoque
        DB::transaction(function () use ($receita, $pesos) {
            foreach ($receita->itens as $item) {
                $item->update(['peso_real' => (float) $pesos[$item->id]]);
                $consumoG = ($item->dosagem_mg / 1000) * ($receita->qtd_capsulas ?? 0);
                $item->lote?->decrement('quantidade', min($item->lote->quantidade, $consumoG));
                $item->produto?->update([
                    'estoque_atual' => max(0, $item->produto->estoque_atual - $consumoG),
                ]);
            }
            $receita->update(['status' => 'pesado']);
        });

        return redirect()->route('receitas.index')
            ->with('sucesso', 'Pesagem confirmada. Status atualizado para "Pesado" e estoque debitado.');
    }

    private function calcularOrcamento(array $itens, int $qtdCapsulas): float
    {
        $total = 0;
        foreach ($itens as $item) {
            $lote  = Lote::with('produto')->find($item['lote_id']);
            $preco = $lote->produto->preco_base ?? 0;
            $total += ($item['dosagem_mg'] / 1000) * $preco * $qtdCapsulas;
        }
        return round($total, 2);
    }

    private function validar(Request $request): array
    {
        return $request->validate(
            [
                'cliente_id'         => ['required', 'exists:clientes,id'],
                'nome_formula'       => ['required', 'string', 'max:255'],
                'medico'             => ['required', 'string', 'max:255'],
                'crm'                => ['required', 'string', 'max:50'],
                'data'               => ['required', 'date'],
                'qtd_capsulas'       => ['required', 'integer', 'min:1'],
                'itens'              => ['required', 'array', 'min:1'],
                'itens.*.lote_id'    => ['required', 'exists:lotes,id'],
                'itens.*.dosagem_mg' => ['required', 'numeric', 'min:0.01'],
            ],
            [
                'cliente_id.required'         => 'Selecione o cliente da receita.',
                'cliente_id.exists'           => 'Cliente não cadastrado no sistema.',
                'nome_formula.required'       => 'O campo Nome da Fórmula é obrigatório.',
                'medico.required'             => 'O campo Médico é obrigatório.',
                'crm.required'                => 'O campo CRM é obrigatório.',
                'data.required'               => 'O campo Data é obrigatório.',
                'qtd_capsulas.required'       => 'O campo Quantidade de Cápsulas é obrigatório.',
                'qtd_capsulas.min'            => 'A quantidade deve ser maior que zero.',
                'itens.required'              => 'Adicione ao menos um insumo à fórmula.',
                'itens.min'                   => 'Adicione ao menos um insumo à fórmula.',
                'itens.*.lote_id.required'    => 'Selecione o lote de cada insumo.',
                'itens.*.lote_id.exists'      => 'Um dos lotes selecionados não existe no sistema.',
                'itens.*.dosagem_mg.required' => 'Informe a dosagem de cada insumo.',
                'itens.*.dosagem_mg.min'      => 'A dosagem deve ser maior que zero.',
            ]
        );
    }
}