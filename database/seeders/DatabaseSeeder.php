<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servico;
use App\Models\Obra;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpa registros anteriores para evitar duplicidade no seeder
        Servico::where('codigo_obra', '44444B')->delete();

        Obra::updateOrCreate(
            ['codigo_obra' => '44444B'],
            [
                'nome' => 'Obra Residencial 44444B',
                'orcamento_aprovado' => 150000.00,
            ]
        );

        $servicos = [
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-01-01 O',
                'tipo'              => 'SINAPI',
                'codigo_item'       => '1',
                'descricao_servico' => 'ADMINISTRAÇÃO LOCAL DA OBRA',
                'unidade'           => 'UN',
                'quantidade'        => 1.00,
                'valor_sem_bdi'     => 20000.00,
                'valor_com_bdi'     => 25000.00,
                'valor_parcela'     => 25000.00,
                'status'            => 'delivered',
                'observacao'        => null,
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-02-01 O',
                'tipo'              => 'SINAPI',
                'codigo_item'       => '2',
                'descricao_servico' => 'MOVIMENTAÇÃO DE TERRA E ESCAVAÇÃO',
                'unidade'           => 'M3',
                'quantidade'        => 350.00,
                'valor_sem_bdi'     => 15000.00,
                'valor_com_bdi'     => 18500.00,
                'valor_parcela'     => 18500.00,
                'status'            => 'delivered',
                'observacao'        => null,
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-03-01 O',
                'tipo'              => 'PRÓPRIA',
                'codigo_item'       => '3',
                'descricao_servico' => 'ESTRUTURA DE CONCRETO ARMADO FCK 25MPA',
                'unidade'           => 'M3',
                'quantidade'        => 120.00,
                'valor_sem_bdi'     => 40000.00,
                'valor_com_bdi'     => 48000.00,
                'valor_parcela'     => 48000.00,
                'status'            => 'delivered',
                'observacao'        => null,
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-04-01 O',
                'tipo'              => 'SINAPI',
                'codigo_item'       => '4',
                'descricao_servico' => 'ALVENARIA DE VEDAÇÃO BLOCO CERÂMICO 9X19X19',
                'unidade'           => 'M2',
                'quantidade'        => 450.00,
                'valor_sem_bdi'     => 13000.00,
                'valor_com_bdi'     => 16200.00,
                'valor_parcela'     => 16200.00,
                'status'            => 'delivered',
                'observacao'        => null,
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-05-01 O',
                'tipo'              => 'PRÓPRIA',
                'codigo_item'       => '5',
                'descricao_servico' => 'ESTRUTURA METÁLICA DE COBERTURA EM AÇO ASTMN A36',
                'unidade'           => 'KG',
                'quantidade'        => 1800.00,
                'valor_sem_bdi'     => 12000.00,
                'valor_com_bdi'     => 14500.00,
                'valor_parcela'     => 14500.00,
                'status'            => 'divergence',
                'observacao'        => 'Divergência de espessura nas vigas perfil I entregues no canteiro',
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-06-01 O',
                'tipo'              => 'SINAPI',
                'codigo_item'       => '6',
                'descricao_servico' => 'REVESTIMENTO ARGAMASSADO REBOCO INTERNO E EXTERNO',
                'unidade'           => 'M2',
                'quantidade'        => 600.00,
                'valor_sem_bdi'     => 8000.00,
                'valor_com_bdi'     => 9800.00,
                'valor_parcela'     => 9800.00,
                'status'            => 'delivered',
                'observacao'        => null,
            ],
            [
                'codigo_obra'       => '44444B',
                'identificacao'     => '01-07-01 O',
                'tipo'              => 'SINAPI',
                'codigo_item'       => '7',
                'descricao_servico' => 'PINTURA LÁTEX ACRÍLICA DUAS DEMÃOS EM PAREDES',
                'unidade'           => 'M2',
                'quantidade'        => 850.00,
                'valor_sem_bdi'     => 6000.00,
                'valor_com_bdi'     => 7200.00,
                'valor_parcela'     => 7200.00,
                'status'            => 'pending',
                'observacao'        => 'Pendente: aguardando tempo de cura e secagem do reboco externo',
            ],
        ];

        foreach ($servicos as $servico) {
            Servico::create($servico);
        }
    }
}
