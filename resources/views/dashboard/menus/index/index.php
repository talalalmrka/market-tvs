<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Menu;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Manage menus')] class extends DashboardPage
{
    public ?Menu $menu = null;

    #[Url(as: 'menu')]
    public ?int $current_menu_id = null;

    public function mount()
    {
        $this->authorize('manage_menus');
        $this->loadMenu();
    }

    public function updatedCurrentMenuId()
    {
        $this->loadMenu();
    }

    protected function loadMenu()
    {
        if (! empty($this->current_menu_id)) {
            $menu = Menu::find($this->current_menu_id);
            if ($menu && $menu instanceof Menu) {
                $this->menu = $menu;
            } else {
                $this->menu = null;
            }
        } else {
            $this->menu = null;
        }
    }

    #[On('menu-created')]
    public function handleMenuCreated($id)
    {
        $this->current_menu_id = $id;
        $this->loadMenu();
    }

    #[On('delete-menu')]
    public function handleDeleteMenu()
    {
        $this->authorize('delete', $this->menu);
        $delete = $this->menu->delete();
        if ($delete) {
            $this->current_menu_id = null;
            $this->loadMenu();
            $this->toastSuccess(__('Menu deleted'));
        } else {
            $this->toastError(__('Delete Menu failed!'));
        }
    }

    public function render()
    {
        return view('dashboard.menus.index.index')->layout('layouts::app.sidebar', [
            'title' => $this->menu ? __('Edit menu (:name)', ['name' => $this->menu->name]) : __('Manage menus'),
        ]);
    }
};
