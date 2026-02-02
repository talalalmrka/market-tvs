<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Screen;
use Livewire\Attributes\Title;

new #[Title('Test')] class extends DashboardPage
{
    public $items = [];

    public function mount()
    {
        $this->items = Screen::first()->timeSlots->first()->slides->toArray();
    }
};
