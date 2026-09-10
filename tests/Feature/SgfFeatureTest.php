<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Servico;
use Tests\TestCase;

class SgfFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_hub_loads_successfully(): void
    {
        Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '01-01',
            'descricao_servico' => 'Alvenaria de Vedação',
            'valor_parcela'     => 15000.00,
            'status'            => 'delivered',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('44444B');
        $response->assertSee('TOTAL GASTO');
        $response->assertSee('15.000,00');
    }

    public function test_dashboard_obra_specific_loads_successfully(): void
    {
        Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '01-01',
            'descricao_servico' => 'Concreto Armado',
            'valor_parcela'     => 50000.00,
            'status'            => 'delivered',
        ]);

        $response = $this->get('/dashboard/44444B');
        $response->assertStatus(200);
        $response->assertSee('Obra 44444B');
        $response->assertSee('Concreto Armado');
    }

    public function test_dedicated_curva_abc_page_loads_successfully(): void
    {
        Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '01-01',
            'descricao_servico' => 'Pintura Acrílica',
            'valor_parcela'     => 12000.00,
            'status'            => 'delivered',
        ]);

        $response = $this->get('/dashboard/44444B/curva-abc');
        $response->assertStatus(200);
        $response->assertSee('Curva ABC de Insumos');
        $response->assertSee('Pintura Acrílica');
    }

    public function test_dedicated_servicos_page_loads_successfully(): void
    {
        Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '01-02',
            'descricao_servico' => 'Instalação Hidráulica',
            'valor_parcela'     => 8000.00,
            'status'            => 'delivered',
        ]);

        $response = $this->get('/dashboard/44444B/servicos');
        $response->assertStatus(200);
        $response->assertSee('Serviços & Composição de Custos', false);
        $response->assertSee('Instalação Hidráulica');
    }

    public function test_dedicated_ocorrencias_page_loads_successfully(): void
    {
        Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '01-03',
            'descricao_servico' => 'Impermeabilização de Laje',
            'valor_parcela'     => 14000.00,
            'status'            => 'divergence',
            'observacao'        => 'Infiltração detectada na junta',
        ]);

        $response = $this->get('/dashboard/44444B/ocorrencias');
        $response->assertStatus(200);
        $response->assertSee('Central de Ocorrências & Inconsistências', false);
        $response->assertSee('Infiltração detectada na junta');
    }

    public function test_update_approved_budget(): void
    {
        $response = $this->post('/dashboard/44444B/orcamento', [
            'orcamento_aprovado' => 100000.00,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('obras', [
            'codigo_obra'        => '44444B',
            'orcamento_aprovado' => 100000.00,
        ]);
    }

    public function test_update_service_status_and_observation(): void
    {
        $servico = Servico::create([
            'codigo_obra'       => '44444B',
            'identificacao'     => '02-01',
            'descricao_servico' => 'Armação de Aço',
            'valor_parcela'     => 20000.00,
            'status'            => 'delivered',
        ]);

        $response = $this->patch("/servicos/{$servico->id}/status", [
            'status'     => 'divergence',
            'observacao' => 'Material entregue com corrosão',
        ]);

        $this->assertDatabaseHas('servicos', [
            'id'         => $servico->id,
            'status'     => 'divergence',
            'observacao' => 'Material entregue com corrosão',
        ]);
    }
}
