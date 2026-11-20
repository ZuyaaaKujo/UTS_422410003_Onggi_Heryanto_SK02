<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function index()
    {
        return view('dashboard.index', [
            'stats' => $this->dashboard->stats(),
            'activity' => $this->dashboard->monthlyActivity(),
            'distribution' => $this->dashboard->categoryDistribution(),
            'recentSessions' => $this->dashboard->recentSessions(),
            'upcomingSessions' => $this->dashboard->upcomingSessions(),
            'spotlight' => $this->dashboard->spotlight(),
            'equipmentOverview' => $this->dashboard->equipmentOverview(),
        ]);
    }
}
