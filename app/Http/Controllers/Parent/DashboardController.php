<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(\App\Services\DashboardService $dashboardService)
    {
        return view('dashboard', [
            'stats' => $dashboardService->getStats(),
            'upcomingFees' => $dashboardService->getUpcomingFees(),
            'latestPayments' => $dashboardService->getLatestPayments(),
            'chartData' => $dashboardService->getChartData()
        ]);
    }
}
