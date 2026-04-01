<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Traits\WithToast;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Async;
use Livewire\Attributes\On;

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
    #[Async]
    public function loadItems()
    {
        $this->items = $this->menu->items->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | SORT (ARRAY ONLY)
    |--------------------------------------------------------------------------
    */

    public function sortMenu($itemId, $newIndex, $parentId)
    {
        // Remove item from current position
        $item = $this->extractItem($this->items, $itemId);

        if (!$item) {
            return;
        }

        // Insert into new location
        $this->insertItem($this->items, $item, $parentId, $newIndex);
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

            if (!empty($item['children'])) {
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

            if (!empty($parent['children'])) {
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
            if (!empty($item['children'])) {
                $this->reindexOrders($item['children']);
            }
        }
    }
    /*
    |--------------------------------------------------------------------------
    | SAVE TO DATABASE
    |--------------------------------------------------------------------------
    */

    protected function persistTree($items, $parentId)
    {
        foreach ($items as $index => $item) {

            MenuItem::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'order'     => $index,
            ]);

            if (!empty($item['children'])) {
                $this->persistTree(
                    $item['children'],
                    $item['id']
                );
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
    #[On('items-added')]
    public function onItemsAddedd(array $items)
    {
        $this->toastSuccess(__('Items added'));
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }
    public function validateItemss($path, $items)
    {
        foreach ($items as $item) {
            $this->validate([
                "{$path}.*.id" => ['required', 'integer', Rule::exists('menu_items', 'id')],
                "{$path}.*.menu_id" => ['required', 'integer', Rule::in([$this->menu->id])],
                "{$path}.*.parent_id" => ['nullable', 'integer', Rule::exists('menu_items', 'id')],
                "{$path}.*.model_type" => ['nullable', 'string', Rule::in(menu_item_model_type_values())],
                "{$path}.*.model_id" => ['nullable', 'integer'],
                "{$path}.*.name" => ['nullable', 'required_without:' . $path . '.icon', 'string', 'max:255'],
                "{$path}.*.icon" => ['nullable', 'required_without:' . $path . '.name', 'string', 'max:255'],
                "{$path}.*.order" => ['required', 'integer'],
                "{$path}.*.url" => ['nullable', 'string', 'max:255'],
                "{$path}.*.class_name" => ['nullable', 'string', 'max:255'],
                "{$path}.*.navigate" => ['boolean'],

            ]);
        }
    }
    public function validateItemsss($path, $items)
    {
        if (empty($items)) {
            return [];
        }

        $validated = [];

        foreach ($items as $index => $item) {

            $currentPath = "{$path}.{$index}";

            // تحقق أساسي من البنية
            validator(
                [$currentPath => $item],
                [
                    "{$currentPath}.id" => [
                        'required',
                        'integer',
                        Rule::exists('menu_items', 'id')
                            ->where(fn($q) => $q->where('menu_id', $this->menu->id)),
                    ],
                    "{$currentPath}.menu_id" => ['required', 'integer', Rule::in([$this->menu->id])],
                    "{$currentPath}.parent_id" => ['nullable', 'integer'],
                    "{$currentPath}.model_type" => ['nullable', 'string', Rule::in(menu_item_model_type_values())],
                    "{$currentPath}.model_id" => ['nullable', 'integer'],
                    "{$currentPath}.name" => ['nullable', 'required_without:' . $currentPath . '.icon', 'string', 'max:255'],
                    "{$currentPath}.icon" => ['nullable', 'required_without:' . $currentPath . '.name', 'string', 'max:255'],
                    "{$currentPath}.order" => ['required', 'integer'],
                    "{$currentPath}.url" => ['nullable', 'string', 'max:255'],
                    "{$currentPath}.class_name" => ['nullable', 'string', 'max:255'],
                    "{$currentPath}.navigate" => ['boolean'],
                ]
            )->validate();

            // تحقق recursive للأبناء
            if (!empty($item['children'])) {
                $this->validateItems(
                    "{$currentPath}.children",
                    $item['children']
                );
            }

            $validated[] = $item;
        }

        return $validated;
    }

    public function validateItems($path, $items)
    {
        if (empty($items)) {
            return [];
        }

        $validated = [];

        foreach ($items as $item) {

            validator(
                $item,
                [
                    'id' => [
                        'required',
                        'integer',
                        Rule::exists('menu_items', 'id')
                            ->where(fn($q) => $q->where('menu_id', $this->menu->id)),
                    ],
                    'menu_id' => ['required', 'integer', Rule::in([$this->menu->id])],
                    'parent_id' => ['nullable', 'integer'],
                    'model_type' => ['nullable', 'string', Rule::in(menu_item_model_type_values())],
                    'model_id' => ['nullable', 'integer'],
                    'name' => ['nullable', 'required_without:icon', 'string', 'max:255'],
                    'icon' => ['nullable', 'required_without:name', 'string', 'max:255'],
                    'order' => ['required', 'integer'],
                    'url' => ['nullable', 'string', 'max:255'],
                    'class_name' => ['nullable', 'string', 'max:255'],
                    'navigate' => ['boolean'],
                ]
            )->validate();

            if (!empty($item['children']) && is_array($item['children'])) {
                $this->validateItems('children', $item['children']);
            }

            $validated[] = $item;
        }

        return $validated;
    }


    public function save()
    {

        $validated = $this->validateItems('items', $this->items);
        dd($this->items, $validated);
        // dd($this->items);
        /* DB::transaction(function () {
            $this->persistTree($this->items, null);
        });

        $this->dispatch('notify', 'Menu saved successfully!'); */
    }
};
