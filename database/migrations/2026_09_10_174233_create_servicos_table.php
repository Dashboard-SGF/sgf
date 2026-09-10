<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('servicos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_obra');        // Ex: 44444B
        $table->string('identificacao');      // Ex: 02-02-01-007 O
        $table->string('tipo')->nullable();  // Ex: PRÓPRIA / SINAPI
        $table->string('codigo_item')->nullable(); // Ex: 1, 2
        $table->text('descricao_servico');   // Ex: ADMINISTRAÇÃO LOCAL...
        $table->string('unidade')->nullable(); // Ex: UN, M2
        $table->decimal('quantidade', 12, 2)->default(0);
        $table->decimal('valor_sem_bdi', 12, 2)->default(0);
        $table->decimal('valor_com_bdi', 12, 2)->default(0);
        $table->decimal('valor_parcela', 12, 2)->default(0); // Valor Total Usado na Curva ABC
        $table->string('status')->default('delivered'); // Padrão para visualização
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicos');
    }
};
