<?php

use App\Livewire\Components\SitePage;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\WithPagination;

new class extends SitePage
{
    use WithPagination;
    #[Locked]
    public User $user;
    public function mount(User $user)
    {
        $this->user = $user;
    }
    #[Computed()]
    public function screens()
    {
        return $this->user->screens()->paginate();
    }
    public function render()
    {
        return view('site.screens.index.index')->layout('layouts.site', [
            'title' => __('Screens for: :user', ['user' => $this->user->name]),
        ]);
    }
};
