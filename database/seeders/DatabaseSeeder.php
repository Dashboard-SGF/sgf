<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Imports\InsumosImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Caminho exato para a planilha oficial do professor
        $filePath = database_path('seeders/files/DATASET_13.xlsx');

        if (File::exists($filePath)) {
            // Importa o arquivo registrando a obra sob o código '44444B'
            Excel::import(new InsumosImport('44444B'), $filePath);
            $this->command->info('Planilha oficial DATASET_13 importada com sucesso para a obra 44444B!');
        } else {
            $this->command->error('Arquivo não encontrado em: ' . $filePath);
        }
    }
}
