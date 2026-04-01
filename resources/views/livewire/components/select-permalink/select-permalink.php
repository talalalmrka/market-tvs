<?php

use App\Option;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    // public $id = '';
    public $label = null;

    public $icon = null;

    public $required = false;

    #[Modelable]
    public $value = [];

    public $error = null;

    public $info = null;

    // public $placeholder = null;
    // public $notIn = null;
    public $search = '';
    /*public $searchCols = [
        'name',
        'slug',
    ];
    public $args = [];*/

    public $primaryKey = 'id';

    public $nameKey = 'name';

    public $limit = 10;

    public $class = null;

    public $size = null;

    public $container_class = null;

    public $dropdown_class = null;

    public function mount()
    {
        $this->value = Arr::only($this->value, ['type', 'model_type', 'model_id']);
    }

    protected function builder()
    {
        $type = data_get($this->value, 'type');
        $morph = model_property($type, 'morph');
        $args = model_property($type, 'args');
        if (empty($morph)) {
            return null;
        }
        $query = app($morph)->query();
        if (is_array($args) && ! empty($args)) {
            $query->where(function ($q) use ($args) {
                foreach ($args as $key => $value) {
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
        $type = data_get($this->value, 'type');
        $searchCols = model_property($type, 'search_cols');
        if (! empty($this->search) && is_array($searchCols) && ! empty($searchCols)) {
            $query->where(function ($q) use ($searchCols) {
                foreach ($searchCols as $col) {
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
                'selected' => $value === data_get($this->value, 'model_id'),
            ]);
        })->toArray();
    }

    protected function getPlaceholder()
    {
        if (! empty($this->placeholder)) {
            return $this->placeholder;
        }
        $type = data_get($this->value, 'type');
        $select_label = model_property($type, 'select_label');

        if (! empty($select_label)) {
            return $select_label;
        }

        return null;
    }

    #[Computed()]
    public function selectLabel()
    {
        $type = data_get($this->value, 'type');

        return model_property($type, 'select_label');
    }

    #[Computed()]
    public function selectedLabel()
    {
        $model_id = data_get($this->value, 'model_id');
        if (! empty($model_id) && ! empty($this->builder())) {
            $model = $this->builder()->find($model_id);
            if ($model) {
                return data_get($model, $this->nameKey);
            }
        }

        return $this->getPlaceholder();
    }

    public function updatedValue($value, $prop)
    {
        if ($prop === 'type') {
            if ($value === 'custom') {
                $this->value['model_type'] = null;
                $this->value['model_id'] = null;
            } else {
                $morph = model_property($value, 'morph');
                if ($this->value['model_type'] !== $morph) {
                    $this->value['model_type'] = $morph;
                    $this->value['model_id'] = null;
                }
            }
        }
    }
};
