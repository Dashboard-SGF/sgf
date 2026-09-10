<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use App\Imports\InsumosImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $repository
    ) {}

    /**
     * Retorna a lista de obras para o Hub.
     */
    public function listarObras(): Collection
    {
        return $this->repository->listarObras();
    }

    /**
     * Retorna os dados completos do dashboard para a obra.
     */
    public function getDashboardData(?string $obra = null, ?string $search = null): array
    {
        $data = $this->repository->getDadosDashboard($obra, $search);

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
                'total_gasto'          => $totalGasto,
                'saldo'                => $saldo,
                'orcamento_total'      => $orcamentoTotal,
                'orcamento_custom'     => $data['orcamento_custom'],
                'percentual_executado' => round($percentualExecutado, 1),
                'pedidos_pendentes'    => $data['pedidos_pendentes'],
                'com_divergencia'      => $data['com_divergencia'],
                'is_estourado'         => $saldo < 0,
            ],
            'insumos_abc' => $insumosComClasse,
            'pedidos'     => $data['pedidos'],
            'ocorrencias' => $data['ocorrencias'],
            'obra_ativa'  => $obra,
        ];
    }

    /**
     * Importa a planilha Excel limpando previamente os registros existentes da mesma obra.
     */
    public function importarPlanilha(UploadedFile $file, ?string $codigoObraInput = null): string
    {
        $codigoObra = $this->determinarCodigoObra($file, $codigoObraInput);

        // 1. Sobrescrita Limpa por Obra: apaga registros antigos da mesma obra
        $this->repository->limparServicosObra($codigoObra);

        // 2. Importa os novos registros
        Excel::import(new InsumosImport($codigoObra), $file);

        return $codigoObra;
    }

    /**
     * Atualiza o orçamento aprovado da obra.
     */
    public function atualizarOrcamento(string $obra, float $valor): void
    {
        $this->repository->atualizarOrcamentoAprovado($obra, $valor);
    }

    /**
     * Atualiza o status e observação do serviço.
     */
    public function atualizarStatusServico(int $servicoId, string $status, ?string $observacao = null): void
    {
        $this->repository->atualizarStatusServico($servicoId, $status, $observacao);
    }

    /**
     * Extrai o código da obra a partir do input, nome do arquivo ou fallback.
     */
    private function determinarCodigoObra(UploadedFile $file, ?string $codigoObraInput): string
    {
        if (!empty(trim($codigoObraInput))) {
            return strtoupper(trim($codigoObraInput));
        }

        // Tenta extrair do nome do arquivo (ex: "44444B.xlsx" -> "44444B")
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName = strtoupper(trim(preg_replace('/[^A-Za-z0-9_-]/', '', $filename)));

        if (!empty($cleanName) && strlen($cleanName) <= 30) {
            return $cleanName;
        }

        return '44444B';
    }

    /**
     * Calcula a Curva ABC (RN-01).
     */
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
                'classe'     => $classe,
                'altura_pct' => round(($item['valor'] / $maiorValor) * 100)
            ]);
        }, $insumos);
    }
}
