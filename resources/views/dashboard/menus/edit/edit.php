<?php

use App\Models\Menu;
use App\Traits\WithToast;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    use WithToast;

    public Menu $menu;

    public $name = '';

    public $position = '';

    public $class_name = '';

    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->fill($this->menu->only('name', 'position', 'class_name'));
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('menus', 'name')->ignore($this->menu?->id)],
            'position' => ['nullable', 'string', Rule::in(menu_positions())],
            'class_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $this->authorize('update', $this->menu);
        $data = $this->validate();
        $update = $this->menu->update($data);
        if ($update) {
            $this->addSuccess('save', __('Menu updated'));
        } else {
            $this->addError('save', __('Update Menu failed!'));
        }
    }

    // Delete current menu
    public function delete()
    {
        $this->authorize('delete', $this->menu);
        $this->dispatch('delete-menu');
        /*$delete = $this->menu->delete();
        if ($delete) {
            $this->dispatch('menu-deleted', id: $menuId);
            $this->toastSuccess(__('Menu deleted'));
            $this->redirect(route('dashboard.menus'), true);
        } else {
            $this->addError('delete_menu', __('Delete Menu failed!'));
        }*/
    }
};
