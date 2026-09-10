<?php

namespace App\Repositories;

use App\Models\Servico;

class DashboardRepository
{
    public function getDadosDashboard(array $filters = []): array
    {
        $query = Servico::query();

        // Filtro por Obra
        if (!empty($filters['obra'])) {
            $query->where('codigo_obra', $filters['obra']);
        }

        // Filtro por Busca Global
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(descricao_servico) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(identificacao) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(tipo) LIKE ?', ["%{$search}%"]);
            });
        }

        $servicos = $query->get();

        $totalGasto = $servicos->sum('valor_parcela');
        $orcamentoTotal = $totalGasto > 0 ? $totalGasto : 0.00;

        // Retorna todos os insumos para a Curva ABC (o scroll da View cuidará da altura)
        $insumos = $servicos->sortByDesc('valor_parcela')->map(function ($s) {
            return [
                'nome'  => $s->descricao_servico,
                'valor' => (float) $s->valor_parcela,
            ];
        })->values()->toArray();

        // Mapeia os serviços para a tabela de Pedidos/Serviços
        $pedidos = $servicos->map(function ($s) {
            return [
                'codigo'       => $s->identificacao,
                'fornecedor'   => $s->tipo ?? 'PRÓPRIA',
                'data'         => $s->created_at ? $s->created_at->format('d/m/Y') : date('d/m/Y'),
                'item'         => $s->descricao_servico,
                'quantidade'   => number_format($s->quantidade, 2, ',', '.') . ' ' . $s->unidade,
                'valor'        => (float) $s->valor_parcela,
                'status'       => $s->status,
                'status_label' => $s->status === 'delivered' ? 'Entregue' : 'Aprovado',
                'obra'         => $s->codigo_obra,
            ];
        })->toArray();

        return [
            'orcamento_total'   => $orcamentoTotal,
            'total_gasto'       => $totalGasto,
            'pedidos_pendentes' => $servicos->where('status', 'pending')->count(),
            'com_divergencia'   => $servicos->where('status', 'divergence')->count(),
            'insumos'           => $insumos,
            'pedidos'           => $pedidos,
            'ocorrencias'       => []
        ];
    }
}
