<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index()
    {
        $stats          = $this->dashboardService->getStats();
        $upcomingFees   = $this->dashboardService->getUpcomingFees();
        $latestPayments = $this->dashboardService->getLatestPayments();
        $chartData      = $this->dashboardService->getChartData();
        $upcomingBirthdays = $this->dashboardService->getUpcomingBirthdays();

        return view('pages.dashboard', compact(
            'stats',
            'upcomingFees',
            'latestPayments',
            'chartData',
            'upcomingBirthdays'
        ));
    }
}
