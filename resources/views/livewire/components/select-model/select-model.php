<?php

use App\Option;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    public $placeholder = null;

    public $notIn = null;

    public $search = '';

    public $searchCols = [
        'name',
        'slug',
    ];

    public $args = [];

    public $primaryKey = 'id';

    public $nameKey = 'name';

    public $model = null;

    public $limit = 10;

    public $class = null;

    public $size = null;

    public $container_class = null;

    public $dropdown_class = null;

    public function mount() {}

    protected function builder()
    {
        if (empty($this->model)) {
            return null;
        }
        $query = app($this->model)->query();
        if (! empty($this->args)) {
            $query->where(function ($q) {
                foreach ($this->args as $key => $value) {
                    $q->where($key, $value);
                }
            });
        }

        return $query;
    }

    protected function query()
    {
        $query = $this->builder();
        if (empty($query)) {
            return collect();
        }
        if (! empty($this->search)) {
            $query->where(function ($q) {
                foreach ($this->searchCols as $col) {
                    $q->orWhere($col, 'like', "%{$this->search}%");
                }
            });
        }

        return $query;
    }

    #[Computed()]
    public function options()
    {
        return $this->query()->limit($this->limit)->get()->map(function (Model $model) {
            $label = data_get($model, $this->nameKey);
            $value = data_get($model, $this->primaryKey);

            return Option::make([
                'label' => $label,
                'value' => $value,
                'selected' => $value === $this->value,
            ]);
        })->toArray();
    }

    protected function getPlaceholder()
    {
        if (! empty($this->placeholder)) {
            return $this->placeholder;
        }
        if (! empty($this->builder())) {
            $table = $this->builder()->getModel()->getTable();
            $single = Str::singular($table);
            $tr = "models.{$single}.single";
            $placeholder = __('Select').' '.__($tr);
            $type = data_get($this->args, 'type');
            if (! empty($type)) {
                $placeholder .= " ({$type})";
            }

            return $placeholder;
        }

        return null;
    }

    #[Computed()]
    public function selectedLabel()
    {
        if (! empty($this->value) && ! empty($this->builder())) {
            $model = $this->builder()->find($this->value);
            if ($model) {
                return data_get($model, $this->nameKey);
            }
        }

        return $this->getPlaceholder();
    }
};
