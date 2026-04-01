<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Menu;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Menu group')] class extends DashboardPage
{
    public ?Menu $menu;

    #[Url(as: 'menu')]
    public ?int $menuId = null;

    public $items = [];

    public function mount()
    {
        $this->loadItems();
    }

    public function updatedMenuId()
    {
        $this->loadItems();
    }

    protected function loadItems()
    {
        if (! empty($this->menuId)) {
            $menu = Menu::find($this->menuId);
            if ($menu && $menu instanceof Menu) {
                $this->menu = $menu;
                $this->items = $this->menu->items->toArray();
            } else {
                $this->menu = null;
                $this->items = [];
            }
        } else {
            $this->menu = null;
            $this->items = [];
        }
    }

    public function save()
    {
        dd($this->items);
    }
};
