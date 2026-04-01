<?php

use App\Models\Menu;
use App\Traits\WithToast;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    use WithToast;

    public $name = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('menus', 'name')],
        ];
    }

    // Create new menu
    public function create()
    {
        $this->authorize('create', Menu::class);
        $this->validate();
        $menu = Menu::create([
            'name' => $this->name,
        ]);
        if ($menu) {
            $menuId = $menu->id;
            $this->reset('name');
            $this->dispatch('menu-created', id: $menuId);
            $this->toastSuccess('Menu created');
        } else {
            $this->toastError('Create menu failed!');
        }
    }
};
