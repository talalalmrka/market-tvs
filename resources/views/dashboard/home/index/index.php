<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Screen;
use App\Models\Slide;
use App\Models\TimeSlot;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Dashboard')] class extends DashboardPage
{

    public function stats()
    {
        return [
            [
                'icon' => 'bi-people',
                'title' => __('Users'),
                'class' => 'overview-card-blue',
                'details' => human_number(User::count()),
            ],
            [
                'icon' => 'bi-tv',
                'title' => __('Screens'),
                'class' => 'overview-card-orange',
                'details' => human_number(Screen::active()->count()) . ' / ' . human_number(Screen::count()),
            ],
            [
                'icon' => 'bi-calendar-range',
                'title' => __('Time Slots'),
                'class' => 'overview-card-teal',
                'details' => human_number(TimeSlot::active()->count()) . ' / ' . human_number(TimeSlot::count()),
            ],
            [
                'icon' => 'bi-card-image',
                'title' => __('Slides'),
                'class' => 'overview-card-pink',
                'details' => human_number(Slide::active()->count()) . ' / ' . human_number(Slide::count()),
            ],
        ];
    }
    #[Computed()]
    public function blocks()
    {
        return [
            view('livewire.dashboard.home.stats', [
                'stats' => $this->stats(),
            ]),
        ];
    }
};
