<?php

use App\Livewire\Components\SitePage;
use App\Models\Screen;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

new class extends SitePage
{
    use WithPagination;
    #[Locked]
    public Screen $screen;
    public function mount(Screen $screen)
    {
        $this->screen = $screen;
    }

    public function render()
    {
        return view('site.screens.show.show')->layout('layouts::app.header', [
            'title' => $this->screen->name,
        ]);
    }
};
