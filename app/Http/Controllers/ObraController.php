<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class ObraController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $obras = $this->dashboardService->listarObras();

        return view('obras.index', compact('obras'));
    }
}
