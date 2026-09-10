<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;
use App\Imports\InsumosImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(?string $obra = null, Request $request): View
    {
        $filters = $request->only(['search', 'start_date', 'end_date']);
        $filters['obra'] = $obra;

        $data = $this->dashboardService->getDashboardData($filters);
        $data['obra_ativa'] = $obra;

        return view('dashboard', $data);
    }

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|mimes:xlsx,xls'
        ]);

        // O código da obra é extraído durante a importação (padrão 44444B)
        Excel::import(new InsumosImport, $request->file('arquivo'));

        return redirect()->route('dashboard', '44444B')->with('success', 'Planilha importada com sucesso!');
    }
}
