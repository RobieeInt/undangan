<?php

namespace App\Livewire\Admin;

use App\Services\AnalyticsService;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];

    public function mount()
    {
        $this->stats = app(AnalyticsService::class)->getAdminDashboardStats();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin');
    }
}
