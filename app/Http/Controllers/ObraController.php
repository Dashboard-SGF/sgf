<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ObraController extends Controller
{
    public function index(): View
    {
        // Agrupa os serviços por código de obra calculando o total orçado e quantidade de itens
        $obras = Servico::select(
            'codigo_obra',
            DB::raw('SUM(valor_parcela) as total_gasto'),
            DB::raw('COUNT(*) as total_itens')
        )
        ->groupBy('codigo_obra')
        ->get();

        return view('obras.index', compact('obras'));
    }
}
