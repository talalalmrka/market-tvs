<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Sortable')] class extends DashboardPage
{
    public $slides = [];
    public function mount()
    {
        $this->slides = collect(range(0, 5))->map(fn($i) => [
            'id' => $i + 1,
            'name' => __('Slide :index', ['index' => $i + 1]),
            'order' => $i,
        ])->toArray();
    }
    public function sortItem($slideId, $newPosition)
    {
        $oldPosition = collect($this->slides)->search(fn($slide, $key) => $slide['id'] == $slideId);
        $removedItem = array_splice($this->slides, $oldPosition, 1);
        // Check if the item was successfully removed
        if (!empty($removedItem)) {
            // 2. Insert the removed element into the new position
            array_splice($this->slides, $newPosition, 0, $removedItem);
        }
        foreach ($this->slides as $i => $slide) {
            $this->slides[$i]['order'] = $i;
        }
    }
};
