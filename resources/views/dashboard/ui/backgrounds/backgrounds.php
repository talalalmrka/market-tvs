<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Backgrounds')] class extends DashboardPage
{
    #[Computed()]
    public function colors(): array
    {
        return config('colors.all', []);
    }
};
