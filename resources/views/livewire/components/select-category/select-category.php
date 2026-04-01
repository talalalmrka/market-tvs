<?php

use App\Models\Category;
use App\Option;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    public $id = '';

    public $type = 'category';

    public $label = null;

    public $icon = null;

    public $required = false;

    #[Modelable]
    public $value;

    public $error = null;

    public $info = null;

    public $placeholder = 'Select Category';

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
        $query = Category::type($this->type);
        if (! empty($this->search)) {
            $query->where(function ($q) {
                foreach ($this->searchCols as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        return $query;
    }

    public function optionLabel(Category $category)
    {
        $label = $category->name;

        return $label;
    }

    #[Computed()]
    public function options()
    {
        return $this->query()->limit($this->limit)->get()->map(fn (Category $category) => Option::make([
            'label' => $category->name,
            'value' => $category->id,
            'selected' => $category->id === $this->value,
        ]))->toArray();
    }

    #[Computed()]
    public function selectedLabel()
    {
        return $this->value ? Category::find($this->value)->name : $this->placeholder;
    }
};
