<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Title;

new #[Title('Sortable')] class extends DashboardPage
{
    public $slides = [];

    public $groups = [];

    public int $currentId = 1;

    public function mount()
    {
        $this->loadSlides();
        $this->loadGroups();
    }

    public function loadSlides()
    {
        $this->slides = collect(range(0, 5))->map(fn ($i) => [
            'id' => $i + 1,
            'name' => __('Slide :index', ['index' => $i + 1]),
            'order' => $i,
        ])->toArray();
    }

    public function groupItems($parentId)
    {
        return collect(range(1, 3))->map(function ($i) use ($parentId) {
            $id = $this->currentId;
            $item = [
                'id' => $this->currentId,
                'parent_id' => $parentId,
                'name' => __('Item :index', ['index' => $i + 1]),
                'order' => $i - 1,
            ];
            $this->currentId++;

            return $item;
        })->toArray();
    }

    public function loadGroups()
    {
        $this->groups = collect(range(1, 3))->map(function ($i) {
            $id = $this->currentId;
            $group = [
                'id' => $this->currentId,
                'name' => __('Group :index', ['index' => $i + 1]),
                'order' => $i - 1,
                'items' => $this->groupItems($id),
            ];
            $this->currentId++;

            return $group;
        })->toArray();
    }

    public function sortItem($slideId, $newPosition)
    {
        $oldPosition = collect($this->slides)->search(fn ($slide, $key) => $slide['id'] == $slideId);
        $removedItem = array_splice($this->slides, $oldPosition, 1);
        // Check if the item was successfully removed
        if (! empty($removedItem)) {
            // 2. Insert the removed element into the new position
            array_splice($this->slides, $newPosition, 0, $removedItem);
        }
        foreach ($this->slides as $i => $slide) {
            $this->slides[$i]['order'] = $i;
        }
    }

    public function handleSort($id, $position, $columnId = null)
    {
        $this->toastSuccess(json_encode([
            'id' => $id,
            'position' => $position,
            'columnId' => $columnId,
        ]));
        // $card = $this->board->cards()->findOrFail($id);

        // Update the card's position and re-order other cards...
    }
};
