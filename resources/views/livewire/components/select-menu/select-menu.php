<?php

use App\Models\Menu;
use App\Option;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    public $id = '';

    public $label = null;

    public $icon = null;

    public $required = false;

    #[Modelable]
    public $value;

    public $error = null;

    public $info = null;

    public $placeholder = 'Select Menu';

    public $notIn = null;

    public $search = '';

    public $searchCols = [
        'name',
        'slug',
    ];

    public $limit = 10;

    public $class = null;

    public $size = null;

    public $container_class = null;

    public $dropdown_class = null;

    public function query()
    {
        $query = Menu::query();
        if (! empty($this->search)) {
            $query->where(function ($q) {
                foreach ($this->searchCols as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        return $query;
    }

    public function optionLabel(Menu $menu)
    {
        $label = $menu->name;

        return $label;
    }

    #[Computed()]
    public function options()
    {
        return $this->query()->limit($this->limit)->get()->map(fn (Menu $menu) => Option::make([
            'label' => $menu->name,
            'value' => $menu->id,
            'selected' => $menu->id === $this->value,
        ]))->toArray();
    }

    #[Computed()]
    public function selectedLabel()
    {
        return $this->value ? Menu::find($this->value)->name : $this->placeholder;
    }
};
