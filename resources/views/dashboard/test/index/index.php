<?php

use App\Models\Screen;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Test')] class extends Component
{
    public $items = [];

    public function mount()
    {
        $this->items = Screen::first()->timeSlots->first()->slides->toArray();
    }
};
