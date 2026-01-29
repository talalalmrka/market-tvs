<?php

use App\Models\Screen;
use App\Models\Slide;
use App\Traits\WithToast;
use Livewire\Component;

new class extends Component
{
    use WithToast;
    public Screen $screen;
    public array $form = [];
    public string $title = '';
    public function mount(Screen $screen)
    {
        $this->screen = $screen;
        $this->form = $screen->toArray();
        $this->title = __('Edit screen :name', ['name' => $this->screen->name]);
    }
    public function sortItem($slideId, $newPosition)
    {
        $slide = Slide::find($slideId);
        if (!$slide) {
            $this->toastError("Slide with id: {$slideId} not found!");
            return;
        }
        $slotId = $slide->time_slot_id;
        $slotPosition = collect($this->form['time_slots'])->search(fn($slot, $key) => $slot['id'] == $slotId);
        $slides = $this->form['time_slots'][$slotPosition]['slides'];
        $oldPosition = collect($slides)->search(fn($slide, $key) => $slide['id'] == $slideId);
        $removedItem = array_splice($slides, $oldPosition, 1);
        // Check if the item was successfully removed
        if (!empty($removedItem)) {
            // 2. Insert the removed element into the new position
            array_splice($slides, $newPosition, 0, $removedItem);
        }
        foreach ($slides as $i => $slide) {
            $slides[$i]['order'] = $i;
        }
        $this->form['time_slots'][$slotPosition]['slides'] = $slides;
        $this->addSuccess("slot-{$slotId}", "Slide order updated item: $slideId, position: $newPosition");
    }
    public function save()
    {
        $this->addSuccess("save", "Saved successfully");
    }
};
