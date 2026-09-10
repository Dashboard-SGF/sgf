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

    public function index(Request $request): View
    {
        $filters = $request->only(['obra', 'search', 'start_date', 'end_date']);
        $data = $this->dashboardService->getDashboardData($filters);
        return view('dashboard', $data);
    }

    public function importar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new InsumosImport, $request->file('arquivo'));

        return redirect()->back()->with('success', 'Planilha importada com sucesso!');
    }
}
