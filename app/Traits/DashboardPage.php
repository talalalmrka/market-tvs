<?php

namespace App\Traits;

use Livewire\Attributes\Layout;

trait DashboardPage
{
    #[Layout('layouts.dashboard')]
    public string $title = 'Dashboard';
    /* public function boot()
    {
        $this->title = $this->title ?? __('Dashboard');
    } */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}
