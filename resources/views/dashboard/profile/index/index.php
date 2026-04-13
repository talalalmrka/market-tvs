<?php

use App\Livewire\Components\DashboardPage;
use App\Models\User;
use Livewire\Attributes\Locked;

new class extends DashboardPage
{
    #[Locked]
    public User $user;

    public function mount($user = null)
    {
        $this->user = $user ?? auth()->user();
        $this->title = __('Profile (:name)', ['name' => $this->user->name]);
    }

    public function render()
    {
        return view('dashboard.profile.index.index')->layout('layouts::app.sidebar', [
            'title' => $this->title,
        ]);
    }
};
