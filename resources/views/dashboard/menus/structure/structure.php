<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Traits\WithToast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    use WithToast;

    public Menu $menu;

    public $items = [];

    public function mount(Menu $menu)
    {
        $this->authorize('update', $menu);
        $this->menu = $menu;
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = $this->menu->items->toArray();
    }

    public function updatedItems($value, $property)
    {
        // dd($property, $value);
        $find = '.type';
        if (Str::endsWith($property, $find)) {
            $limit = Str::length($property) - Str::length($find);
            $prefix = Str::limit($property, $limit, '');
            $modelTypeKey = "items.{$prefix}.model_type";
            $modelIdKey = "items.{$prefix}.model_id";
            if ($value === 'custom') {
                $this->fill([
                    $modelTypeKey => null,
                    $modelIdKey => null,
                ]);
            } else {
                $morph = model_property($value, 'morph');
                $this->fill([
                    $modelTypeKey => $morph,
                    $modelIdKey => null,
                ]);
            }
        }
    }

    public function removeItem($id)
    {
        $removed = $this->extractItem($this->items, $id);

        if ($removed) {
            $this->reindexOrders($this->items);
        }
    }

    public function delete(MenuItem $item)
    {
        $this->authorize('update', $this->menu);
        $itemId = $item->id;
        $delete = $item->delete();
        if ($delete) {
            $this->removeItem($itemId);
            $this->toastSuccess(__('Item deleted'));
        }
    }

    public function setParent(?int $id)
    {
        $this->dispatch('set-parent', id: $id);
    }

    #[On('items-added')]
    public function onItemsAddedd(array $items)
    {
        $this->toastSuccess(__('Items added'));
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SORT (ARRAY ONLY)
    |--------------------------------------------------------------------------
    */

    public function sortMenu($itemId, $position, $parentId)
    {
        if (empty($parentId)) {
            $parentId = null;
        }
        $this->toastSuccess(json_encode([
            'itemId' => $itemId,
            'position' => $position,
            'parentId' => $parentId,
        ]));
        // Remove item from current position
        $item = $this->extractItem($this->items, $itemId);
        if (! $item) {
            return;
        }
        // Insert into new location
        $this->insertItem($this->items, $item, $parentId, $position);
        $this->reindexOrders($this->items);
    }

    protected function extractItem(&$items, $itemId)
    {
        foreach ($items as $index => &$item) {
            if ($item['id'] == $itemId) {
                $removed = $item;
                array_splice($items, $index, 1);

                return $removed;
            }
            if (! empty($item['children'])) {
                $found = $this->extractItem($item['children'], $itemId);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }

    protected function insertItem(&$items, $item, $parentId, $position)
    {
        $item['parent_id'] = $parentId;
        if (empty($parentId)) {
            array_splice($items, $position, 0, [$item]);

            return;
        }
        foreach ($items as &$parent) {
            if ($parent['id'] == $parentId) {
                $parent['children'] ??= [];
                array_splice(
                    $parent['children'],
                    $position,
                    0,
                    [$item]
                );

                return;
            }

            if (! empty($parent['children'])) {
                $this->insertItem(
                    $parent['children'],
                    $item,
                    $parentId,
                    $position
                );
            }
        }
    }

    protected function reindexOrders(&$items)
    {
        foreach ($items as $index => &$item) {
            $item['order'] = $index;
            if (! empty($item['children'])) {
                $this->reindexOrders($item['children']);
            }
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

    protected function getIcons(array $items)
    {
        $icons = '';
        foreach ($items as $item) {
            $icon = data_get($item, 'icon');
            if (! empty($icon)) {
                $icons .= "<i class=\"icon {$icon}\"></i>";
            }
        }

        return $icons;
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
            // $this->saveIcons($items);
            $this->loadItems();
            $this->addSuccess('save', __('Saved'));
        } catch (\Exception $ex) {
            $this->addError('save', $ex->getMessage());
        }
    }

    public function saveIcons(array $items)
    {
        try {
            $icons = $this->getIcons($items);
            $dir = resource_path('views/icons');
            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $path = $dir.'/bootstrap-icons.blade.php';
            $put = File::put($path, $icons);
            if (! $put) {
                $this->toastError(__('Failed to save icons file!'));
            }
        } catch (\Exception $e) {
            $this->toastError($e->getMessage());
        }
    }
};
