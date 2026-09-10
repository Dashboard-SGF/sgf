<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_obra',
        'identificacao',
        'tipo',
        'codigo_item',
        'descricao_servico',
        'unidade',
        'quantidade',
        'valor_sem_bdi',
        'valor_com_bdi',
        'valor_parcela',
        'status',
    ];
}
