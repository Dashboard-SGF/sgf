<?php

namespace App\Imports;

use App\Models\Servico;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InsumosImport implements ToModel, WithStartRow
{
    protected string $codigoObra;

    public function __construct(string $codigoObra = '44444B')
    {
        $this->codigoObra = $codigoObra;
    }

    public function startRow(): int
    {
        return 2;
    }

    private function formatDecimal($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove R$, espaços e converte formato de moeda BR (1.000,50) para float (1000.50)
        $clean = preg_replace('/[^\d,-]/', '', (string) $value);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }

    public function model(array $row)
    {
        if (empty($row[0]) || empty($row[3]) || empty($row[5])) {
            return null;
        }

        return new Servico([
            'codigo_obra'       => $this->codigoObra,
            'identificacao'     => $row[0],
            'tipo'              => $row[1] ?? 'PRÓPRIA',
            'codigo_item'       => $row[2] ?? null,
            'descricao_servico' => $row[3],
            'unidade'           => $row[4] ?? 'UN',
            'quantidade'        => $this->formatDecimal($row[5]),
            'valor_sem_bdi'     => $this->formatDecimal($row[6]),
            'valor_com_bdi'     => $this->formatDecimal($row[7]),
            'valor_parcela'     => $this->formatDecimal($row[8]),
            'status'            => 'delivered',
        ]);
    }
}
