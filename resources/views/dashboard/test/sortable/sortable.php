<?php

use Livewire\Component;
use App\Models\Screen;
use App\Models\TimeSlot;
use App\Models\Slide;
use App\Traits\WithToast;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    use WithToast;
    public $slides = [];
    public string $class = '';

    public function mount()
    {
        $this->loadSlides();
    }
    public function loadSlides()
    {
        $this->slides = Screen::first()
            ->timeSlots()
            ->with(['slides' => fn($q) => $q->orderBy('order')])
            ->first()
            ->slides
            ->toArray();
    }
    public function sortItem(int $slideId, int $newPosition): void
    {
        $slides = $this->slides;
        $oldPosition = collect($slides)->search(fn($item, $key) => $item['id'] == $slideId);
        $removedItem = array_splice($slides, $oldPosition, 1);
        // Check if the item was successfully removed
        if (!empty($removedItem)) {
            // 2. Insert the removed element into the new position
            array_splice($slides, $newPosition, 0, $removedItem);
        }
        foreach ($slides as $i => $slide) {
            $slides[$i]['order'] = $i;
        }
        $this->slides = $slides;
        $this->notify($slideId, $newPosition);
    }


    public function sortItemSave(int $slideId, int $newPosition): void
    {
        DB::transaction(function () use ($slideId, $newPosition) {

            $slide = Slide::where('id', $slideId)
                ->where('time_slot_id', $this->timeSlot->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldPosition = $slide->order;

            if ($oldPosition === $newPosition) {
                return;
            }

            if ($newPosition > $oldPosition) {
                Slide::where('time_slot_id', $this->timeSlot->id)
                    ->whereBetween('order', [$oldPosition + 1, $newPosition])
                    ->decrement('order');
            } else {
                Slide::where('time_slot_id', $this->timeSlot->id)
                    ->whereBetween('order', [$newPosition, $oldPosition - 1])
                    ->increment('order');
            }

            $slide->update([
                'order' => $newPosition,
            ]);
        });

        $this->loadTimeSlot();
        $this->notify($slideId, $newPosition);
    }

    public function notify(int $slideId, $newPosition)
    {
        $this->addSuccess("sort", "Slide order updated item: $slideId, position: $newPosition");
    }
};
