<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics) {}

    public function index()
    {
        $stats = $this->analytics->getAdminDashboardStats();
        return view('admin.dashboard', compact('stats'));
    }
}
