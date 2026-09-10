<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(?string $obra = null, Request $request): View
    {
        $search = $request->input('search');
        $data = $this->dashboardService->getDashboardData($obra, $search);
        $data['active_tab'] = 'overview';

        return view('dashboard', $data);
    }

    public function curvaAbc(?string $obra = null, Request $request): View
    {
        $search = $request->input('search');
        $data = $this->dashboardService->getDashboardData($obra, $search);
        $data['active_tab'] = 'curva-abc';

        return view('dashboard.curva_abc', $data);
    }

    public function servicos(?string $obra = null, Request $request): View
    {
        $search = $request->input('search');
        $data = $this->dashboardService->getDashboardData($obra, $search);
        $data['active_tab'] = 'servicos';

        return view('dashboard.servicos', $data);
    }

    public function ocorrencias(?string $obra = null, Request $request): View
    {
        $search = $request->input('search');
        $data = $this->dashboardService->getDashboardData($obra, $search);
        $data['active_tab'] = 'ocorrencias';

        return view('dashboard.ocorrencias', $data);
    }

    public function importar(Request $request): RedirectResponse
    {
        $request->validate([
            'arquivo'     => 'required|mimes:xlsx,xls',
            'codigo_obra' => 'nullable|string|max:30',
        ]);

        $codigoObra = $this->dashboardService->importarPlanilha(
            $request->file('arquivo'),
            $request->input('codigo_obra')
        );

        return redirect()
            ->route('dashboard', $codigoObra)
            ->with('success', "Planilha importada e dados atualizados com sucesso para a obra {$codigoObra}!");
    }

    public function atualizarOrcamento(Request $request, string $obra): RedirectResponse
    {
        $request->validate([
            'orcamento_aprovado' => 'required|numeric|min:0',
        ]);

        $this->dashboardService->atualizarOrcamento(
            $obra,
            (float) $request->input('orcamento_aprovado')
        );

        return redirect()
            ->back()
            ->with('success', 'Orçamento aprovado atualizado com sucesso!');
    }

    public function atualizarStatus(Request $request, int $servico): RedirectResponse|JsonResponse
    {
        $request->validate([
            'status'     => 'required|in:delivered,pending,divergence,approved',
            'observacao' => 'nullable|string|max:1000',
        ]);

        $this->dashboardService->atualizarStatusServico(
            $servico,
            $request->input('status'),
            $request->input('observacao')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status do serviço atualizado com sucesso!',
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Status do serviço atualizado com sucesso!');
    }
}
