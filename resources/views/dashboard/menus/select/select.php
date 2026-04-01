<?php

use App\Models\Menu;
use App\Option;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    #[Modelable]
    public $value;

    public $search = '';

    public $searchCols = [
        'name',
        'slug',
    ];

    public $limit = 10;

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
        if (! empty($this->value)) {
            $menu = Menu::find($this->value);
            if ($menu) {
                return $menu->name;
            }
        }

        return __('Select menu');
    }
};
