<?php

namespace App\Repositories;

use App\Models\Servico;
use App\Models\Obra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DashboardRepository
{
    /**
     * Lista todas as obras cadastradas agrupando o total gasto e total de itens.
     */
    public function listarObras(): Collection
    {
        $obrasServicos = Servico::select(
            'codigo_obra',
            DB::raw('SUM(valor_parcela) as total_gasto'),
            DB::raw('COUNT(*) as total_itens')
        )
        ->groupBy('codigo_obra')
        ->get();

        $configObras = Obra::all()->keyBy('codigo_obra');

        return $obrasServicos->map(function ($item) use ($configObras) {
            $config = $configObras->get($item->codigo_obra);
            $item->orcamento_aprovado = $config ? $config->orcamento_aprovado : null;
            return $item;
        });
    }

    /**
     * Obtém os dados do dashboard para a obra ativa e filtros informados.
     */
    public function getDadosDashboard(?string $obra = null, ?string $search = null): array
    {
        $queryBase = Servico::query();
        if (!empty($obra)) {
            $queryBase->where('codigo_obra', $obra);
        }

        // Serviços para contagem de KPIs (sem filtro de busca textual)
        $servicosBase = (clone $queryBase)->get();

        // Aplicar busca global textual se informada
        if (!empty($search)) {
            $searchTerm = strtolower(trim($search));
            $queryBase->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(descricao_servico) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(identificacao) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(codigo_item) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(tipo) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        $servicos = $queryBase->get();

        $totalGasto = $servicosBase->sum('valor_parcela');

        // Busca orçamento aprovado cadastrado para a obra
        $orcamentoAprovado = null;
        if (!empty($obra)) {
            $obraModel = Obra::where('codigo_obra', $obra)->first();
            if ($obraModel && $obraModel->orcamento_aprovado !== null) {
                $orcamentoAprovado = (float) $obraModel->orcamento_aprovado;
            }
        }

        $orcamentoTotal = $orcamentoAprovado !== null ? $orcamentoAprovado : $totalGasto;

        // Insumos para Curva ABC
        $insumos = $servicos->sortByDesc('valor_parcela')->map(function ($s) {
            return [
                'id'    => $s->id,
                'nome'  => $s->descricao_servico,
                'valor' => (float) $s->valor_parcela,
            ];
        })->values()->toArray();

        // Tabela de Pedidos / Serviços
        $pedidos = $servicos->map(function ($s) {
            return [
                'id'           => $s->id,
                'codigo'       => $s->identificacao,
                'codigo_item'  => $s->codigo_item,
                'fornecedor'   => $s->tipo ?? 'PRÓPRIA',
                'data'         => $s->created_at ? $s->created_at->format('d/m/Y') : date('d/m/Y'),
                'item'         => $s->descricao_servico,
                'quantidade'   => number_format($s->quantidade, 2, ',', '.') . ' ' . ($s->unidade ?? 'UN'),
                'valor'        => (float) $s->valor_parcela,
                'status'       => $s->status ?? 'delivered',
                'status_label' => $this->formatStatusLabel($s->status ?? 'delivered'),
                'observacao'   => $s->observacao,
                'obra'         => $s->codigo_obra,
            ];
        })->toArray();

        // Central de Ocorrências (itens com status pendente/divergência ou com observação)
        $ocorrencias = $servicosBase->filter(function ($s) {
            return in_array($s->status, ['pending', 'divergence']) || !empty($s->observacao);
        })->map(function ($s) {
            return [
                'id'         => $s->id,
                'codigo'     => $s->identificacao,
                'desc'       => $s->descricao_servico,
                'status'     => $s->status,
                'tag'        => $this->formatStatusLabel($s->status),
                'observacao' => $s->observacao,
            ];
        })->values()->toArray();

        return [
            'orcamento_total'   => $orcamentoTotal,
            'orcamento_custom'  => $orcamentoAprovado !== null,
            'total_gasto'       => $totalGasto,
            'pedidos_pendentes' => $servicosBase->where('status', 'pending')->count(),
            'com_divergencia'   => $servicosBase->where('status', 'divergence')->count(),
            'insumos'           => $insumos,
            'pedidos'           => $pedidos,
            'ocorrencias'       => $ocorrencias,
        ];
    }

    /**
     * Limpa os serviços salvos anteriormente de uma mesma obra antes da reimportação (Sobrescrita Limpa).
     */
    public function limparServicosObra(string $codigoObra): void
    {
        Servico::where('codigo_obra', $codigoObra)->delete();
    }

    /**
     * Atualiza ou cria a meta de orçamento aprovado para a obra.
     */
    public function atualizarOrcamentoAprovado(string $codigoObra, float $valor): Obra
    {
        return Obra::updateOrCreate(
            ['codigo_obra' => $codigoObra],
            ['orcamento_aprovado' => $valor]
        );
    }

    /**
     * Atualiza o status e a observação de um serviço.
     */
    public function atualizarStatusServico(int $servicoId, string $status, ?string $observacao = null): Servico
    {
        $servico = Servico::findOrFail($servicoId);
        $servico->update([
            'status'     => $status,
            'observacao' => $observacao,
        ]);

        return $servico;
    }

    private function formatStatusLabel(string $status): string
    {
        return match ($status) {
            'pending'    => 'Pendente',
            'divergence' => 'Com Divergência',
            'approved'   => 'Aprovado',
            default      => 'Entregue / Normal',
        };
    }
}
