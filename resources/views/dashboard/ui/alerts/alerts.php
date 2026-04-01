<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Alerts')] class extends DashboardPage
{
    #[Computed()]
    public function types()
    {
        return collect([
            'info',
            'success',
            'warning',
            'error',
        ]);
    }

    #[Computed()]
    public function sizes()
    {
        return collect([
            'info',
            'success',
            'warning',
            'error',
        ]);
    }
};
