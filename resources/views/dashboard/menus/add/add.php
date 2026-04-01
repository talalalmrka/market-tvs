<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Traits\WithToast;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    use WithToast;

    public Menu $menu;

    public ?int $parentId = null;

    public $selected = [];

    public $selectAll = [];

    public $search = [];

    public $custom = [
        'name' => '',
        'icon' => '',
        'url' => '',
    ];

    public function mount(Menu $menu)
    {
        $this->authorize('update', $menu);
        $this->menu = $menu;
        $this->initialize();
    }

    #[Computed()]
    public function cardTitle()
    {
        $parentName = __('Top');
        if (! empty($this->parentId)) {
            $item = MenuItem::find($this->parentId);
            if ($item) {
                $parentName = $item->name;
            }
        }

        return $parentName;
    }

    protected function builder()
    {
        return models_with('permalink');
    }

    protected function modelRules($id)
    {
        $rules = [];
        $model = model_data($id);
        if (empty($model)) {
            return $rules;
        }
        $table = data_get($model, 'table');
        $args = data_get($model, 'args');
        $existsRule = Rule::exists($table, 'id');
        if (is_array($args) && ! empty($args)) {
            foreach ($args as $key => $value) {
                $existsRule->where($key, $value);
            }
        }

        return [
            'required',
            $existsRule,
        ];
    }

    public function initialize()
    {
        foreach ($this->builder() as $item) {
            $id = data_get($item, 'id');
            // selected
            if (! isset($this->selected[$id])) {
                $this->selected[$id] = [];
            }

            // selectAll
            if (! isset($this->selectAll[$id])) {
                $this->selectAll[$id] = false;
            }

            // search
            if (! isset($this->search[$id])) {
                $this->search[$id] = '';
            }
        }
    }

    #[Computed()]
    public function models()
    {
        return $this->builder()->map(fn ($model) => [
            'id' => data_get($model, 'id'),
            'title' => data_get($model, 'plural'),
            'icon' => data_get($model, 'icon'),
        ])->toArray();
    }

    #[Computed()]
    public function modelItems(string $id)
    {
        $morph = model_property($id, 'morph');
        if (empty($morph)) {
            return collect();
        }
        $query = app($morph)->query();

        if (! $query) {
            return collect();
        }
        $args = model_property($id, 'args');
        if (is_array($args) && ! empty($args)) {
            $query->where(function ($q) use ($args) {
                foreach ($args as $key => $value) {
                    $q->where($key, $value);
                }
            });
        }
        $search = data_get($this->search, $id);
        $searchCols = model_property($id, 'search_cols');

        if (! empty($search) && ! empty($searchCols)) {
            $query->where(function ($q) use ($searchCols, $search) {
                foreach ($searchCols as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        return $query->paginate();
    }

    public function updatedSelectAll($val, $prop)
    {
        if ($val) {
            $this->selected[$prop] = $this->modelItems($prop)->pluck('id')->toArray();
        } else {
            $this->selected[$prop] = [];
        }
    }

    #[Computed()]
    public function modelQuery($id)
    {
        $morph = model_property($id, 'morph');

        return app($morph)->query();
    }

    public function addItems($id)
    {
        $this->authorize('update', $this->menu);
        $validated = $this->validate([
            "selected.{$id}" => ['required', 'array'],
            "selected.{$id}.*" => $this->modelRules($id),
        ]);
        try {
            $selected = data_get($validated, "selected.{$id}");
            $items = [];
            foreach ($selected as $itemableId) {
                $query = $this->modelQuery($id);
                $model = $query->find($itemableId);
                if ($model) {
                    $created = $this->menu->items()->create([
                        'parent_id' => null,
                        'name' => $model->name,
                        'model_type' => $model->getMorphClass(),
                        'model_id' => $model->id,
                    ]);
                    if ($created) {
                        $itemId = $created->id;
                        $item = MenuItem::find($itemId);
                        if ($item) {
                            $items[] = $item->toArray();
                        }
                    }
                }
            }
            $this->reset("selected.{$id}");
            $this->sendAdded($items);
            $statusKey = "add_{$id}";
            $this->addSuccess($statusKey, __(':id added', ['id' => $id]));
        } catch (\Exception $e) {
            $this->addError($statusKey, $e->getMessage());
        }
    }

    public function addCustom()
    {
        $validated = $this->validate([
            'custom.name' => ['nullable', 'required_without:custom.icon'],
            'custom.icon' => ['nullable', 'required_without:custom.name', 'string', 'max: 255'],
            'custom.url' => ['required', 'string', 'max: 255'],
        ]);
        $data = data_get($validated, 'custom');
        $create = $this->menu->items()->create($data);

        if ($create) {
            $items = [];
            $id = $create->id;
            $item = MenuItem::find($id);
            if ($item) {
                $items[] = $item->toArray();
            }
            $this->reset('custom');
            $this->sendAdded($items);
            $this->addSuccess('add_custom', __('Custom link added'));
        } else {
            $this->addError('add_custom', __('Failed to add custom link'));
        }
    }

    public function sendAdded(array $items)
    {
        $this->dispatch('items-added', items: $items);
    }

    #[On('set-parent')]
    public function handleSetParent(?int $id)
    {
        $this->parentId = $id;
    }
};
