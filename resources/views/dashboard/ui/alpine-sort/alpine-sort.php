<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Alpine sort')] class extends DashboardPage
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

    public function itemsRules($path, $items)
    {
        $rules = [];
        foreach ($items as $i => $item) {
            $currentPath = "{$path}.{$i}";
            $itemRules = [
                "{$currentPath}.id" => [
                    'required',
                    'integer',
                    Rule::exists('menu_items', 'id')
                        ->where(fn ($q) => $q->where('menu_id', $this->menu->id)),
                ],
                "{$currentPath}.menu_id" => ['required', 'integer', Rule::in([$this->menu->id])],
                "{$currentPath}.parent_id" => ['nullable', 'integer'],
                "{$currentPath}.model_type" => ['nullable', 'string', Rule::in(model_morphs())],
                "{$currentPath}.model_id" => ['nullable', 'integer'],
                "{$currentPath}.name" => ['nullable', "required_without:{$currentPath}.icon", 'string', 'max:255'],
                "{$currentPath}.icon" => ['nullable', "required_without:{$currentPath}.name", 'string', 'max:255'],
                "{$currentPath}.order" => ['required', 'integer'],
                "{$currentPath}.url" => ['nullable', 'string', 'max:255'],
                "{$currentPath}.class_name" => ['nullable', 'string', 'max:255'],
                "{$currentPath}.navigate" => ['boolean'],
            ];
            $rules = array_merge($rules, $itemRules);
            $children = data_get($item, 'children');
            if (! empty($children)) {
                $rules = array_merge(
                    $rules,
                    $this->itemsRules("{$currentPath}.children", $children)
                );
            }
        }

        return $rules;
    }

    protected function flattenItems(array $items, array &$flat = []): array
    {
        foreach ($items as $item) {

            $children = $item['children'] ?? [];
            unset($item['children']);

            $flat[] = $item;

            if (! empty($children) && is_array($children)) {
                $this->flattenItems($children, $flat);
            }
        }

        return $flat;
    }

    public function save()
    {
        $this->authorize('update', $this->menu);
        $rules = $this->itemsRules('items', $this->items);
        $validated = $this->validate($rules);
        $items = data_get($validated, 'items', []);
        try {
            $flat = [];
            $this->flattenItems($items, $flat);
            DB::transaction(function () use ($flat) {
                foreach ($flat as $item) {
                    MenuItem::where('id', $item['id'])
                        ->where('menu_id', $this->menu->id)
                        ->update($item);
                }
            });
            $this->loadItems();
            $this->addSuccess('save', __('Saved'));
        } catch (\Exception $ex) {
            $this->addError('save', $ex->getMessage());
        }
    }
};
