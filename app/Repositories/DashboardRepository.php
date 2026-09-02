<?php

namespace App\Repositories;

class DashboardRepository
{
    public function getDadosDashboard(): array
    {
        return [
            'orcamento_total' => 1235000.00,
            'total_gasto' => 842650.00,
            'pedidos_pendentes' => 18,
            'com_divergencia' => 7,
            'insumos' => [
                ['nome' => 'Cimento', 'valor' => 280000.00],
                ['nome' => 'Aço', 'valor' => 240000.00],
                ['nome' => 'Blocos', 'valor' => 180000.00],
                ['nome' => 'Areia', 'valor' => 60000.00],
                ['nome' => 'Tubos', 'valor' => 35000.00],
                ['nome' => 'Cabos', 'valor' => 25000.00],
                ['nome' => 'Tinta', 'valor' => 12000.00],
                ['nome' => 'Louças', 'valor' => 8000.00],
                ['nome' => 'Outros', 'valor' => 2650.00],
            ],
            'pedidos' => [
                ['codigo' => 'PD-2026-0148', 'fornecedor' => 'Construmax Ltda.', 'data' => '02/09/2026', 'item' => 'Aço CA-50 10mm', 'quantidade' => '2.500 kg', 'valor' => 21750.00, 'status' => 'approved', 'status_label' => 'Aprovado'],
                ['codigo' => 'PD-2026-0147', 'fornecedor' => 'Materiais Bahia', 'data' => '01/09/2026', 'item' => 'Cimento CP-II', 'quantidade' => '800 sc', 'valor' => 28800.00, 'status' => 'pending', 'status_label' => 'Pendente'],
                ['codigo' => 'PD-2026-0146', 'fornecedor' => 'Hidroforte', 'data' => '31/08/2026', 'item' => 'Tubos PVC 100mm', 'quantidade' => '350 un', 'valor' => 14700.00, 'status' => 'delivered', 'status_label' => 'Entregue'],
                ['codigo' => 'PD-2026-0145', 'fornecedor' => 'Elétrica Prime', 'data' => '30/08/2026', 'item' => 'Cabo flexível 10mm', 'quantidade' => '1.200 m', 'valor' => 19440.00, 'status' => 'divergence', 'status_label' => 'Divergência'],
            ],
            'ocorrencias' => [
                ['codigo' => 'PD-2026-0145', 'desc' => 'Cabo flexível entregue com especificação diferente da solicitada.', 'tag' => '⚠ Especificação incorreta'],
                ['codigo' => 'PD-2026-0139', 'desc' => 'Recebimento parcial: 120 de 200 unidades previstas.', 'tag' => '⚠ Entrega parcial'],
                ['codigo' => 'PD-2026-0134', 'desc' => 'Material recebido apresenta avarias em parte da carga.', 'tag' => '⚠ Avaria'],
            ]
        ];
    }
}
