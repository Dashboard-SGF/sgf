<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $repository
    ) {}

    public function getDashboardData(array $filters = []): array
    {
        $data = $this->repository->getDadosDashboard($filters);

        $totalGasto = $data['total_gasto'];
        $orcamentoTotal = $data['orcamento_total'];

        $saldo = $orcamentoTotal - $totalGasto;
        $percentualExecutado = $orcamentoTotal > 0
            ? ($totalGasto / $orcamentoTotal) * 100
            : 0;

        // Regra da Curva ABC (RN-01)
        $insumosComClasse = $this->calcularCurvaABC($data['insumos'], $totalGasto);

        return [
            'kpis' => [
                'total_gasto' => $totalGasto,
                'saldo' => $saldo,
                'orcamento_total' => $orcamentoTotal,
                'percentual_executado' => round($percentualExecutado, 1),
                'pedidos_pendentes' => $data['pedidos_pendentes'],
                'com_divergencia' => $data['com_divergencia'],
                'is_estourado' => $saldo < 0,
            ],
            'insumos_abc' => $insumosComClasse,
            'pedidos' => $data['pedidos'],
            'ocorrencias' => $data['ocorrencias']
        ];
    }

    private function calcularCurvaABC(array $insumos, float $totalGasto): array
    {
        if (empty($insumos) || $totalGasto <= 0) {
            return [];
        }

        usort($insumos, fn($a, $b) => $b['valor'] <=> $a['valor']);

        $acumulado = 0;

        return array_map(function ($item) use (&$acumulado, $totalGasto, $insumos) {
            $acumulado += $item['valor'];
            $pctAcumulado = ($acumulado / $totalGasto) * 100;

            if ($pctAcumulado <= 80) {
                $classe = 'a';
            } elseif ($pctAcumulado <= 95) {
                $classe = 'b';
            } else {
                $classe = 'c';
            }

            $maiorValor = $insumos[0]['valor'] > 0 ? $insumos[0]['valor'] : 1;

            return array_merge($item, [
                'classe' => $classe,
                'altura_pct' => round(($item['valor'] / $maiorValor) * 100)
            ]);
        }, $insumos);
    }
}
