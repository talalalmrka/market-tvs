<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Screen;
use App\Models\Slide;
use App\Models\TimeSlot;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;

new #[Title('Edit screen')] class extends DashboardPage
{
    #[Locked]
    public Screen $screen;
    public array $form = [];
    public string $title = '';
    public bool $showSlideModal = false;
    public ?Slide $slideToEdit = null;
    public $file = null;
    public ?int $fileSlot = null;
    public function mount(Screen $screen)
    {
        $this->screen = $screen;
        $this->form = $this->screen->toArray();
        $this->title = __('Edit screen :name', ['name' => $this->screen->name]);
    }
    protected function rules()
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('screens', 'slug')
                    ->where(fn($q) => $q->where('user_id', $this->screen->user_id))
                    ->ignore($this->screen->id),
            ],
            'form.is_active' => ['boolean'],
            'form.time_slots.*.id' => [
                'required',
                Rule::exists('time_slots', 'id')
                    ->where(fn($q) => $q->where('screen_id', $this->screen->id))
            ],
            'form.time_slots.*.screen_id' => [
                'required',
                Rule::exists('screens', 'id')
                    ->where(fn($q) => $q->where('user_id', $this->screen->user_id))
            ],
            'form.time_slots.*.name' => ['required', 'string', 'max:255'],
            'form.time_slots.*.start_time' => ['required', 'date_format:H:i'],
            'form.time_slots.*.end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'form.time_slots.*.duration' => ['required', 'integer', 'max:100000'],
            'form.time_slots.*.is_active' => ['boolean'],
            'form.time_slots.*.slides.*.id' => ['required', 'integer', Rule::exists('slides', 'id')],
            'form.time_slots.*.slides.*.time_slot_id' => ['required', 'integer', Rule::exists('time_slots', 'id')],
            'form.time_slots.*.slides.*.name' => ['nullable', 'string', 'max:255'],
            'form.time_slots.*.slides.*.duration' => ['required', 'integer', 'max:100000'],
            'form.time_slots.*.slides.*.transition' => ['required', 'string', Rule::in(slide_transition_values())],
            'form.time_slots.*.slides.*.order' => ['required', 'integer', 'max:100000'],
            'form.time_slots.*.slides.*.is_active' => ['boolean'],
        ];
    }
    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }
    public function addSlot()
    {
        $slot = TimeSlot::create([
            'screen_id' => $this->screen->id,
            'name' => __('Time slot :number', ['number' => $this->screen->timeSlots()->count() + 1]),
            'start_time' => '00:00',
            'end_time' => '00:00',
        ]);
        if ($slot) {
            $slot = TimeSlot::find($slot->id);
            $this->form['time_slots'][] = $slot->toArray();
            $this->toastSuccess(__('Time slot added successfully'));
        }
    }
    public function updatedFile()
    {
        $this->validate([
            'file' => [
                'nullable',
                'file',
                'max:200000',
                'mimetypes:image/jpeg,image/png,image/bmp,image/gif,image/svg+xml,video/mp4,video/mpeg,video/ogg,video/webm,video/quicktime'
            ]
        ]);
        if ($this->file) {
            $slot = TimeSlot::find($this->fileSlot);
            if (!$slot) {
                $this->toastError(__("Slot with id: :id not found!", ['id' => $this->fileSlot]));
                return;
            }
            $order = $slot->slides()->count();
            $slide = Slide::create([
                'time_slot_id' => $this->fileSlot,
                'order' => $order,
            ]);
            if (!$slide) {
                $this->toastError("Creating slide failed!");
                return;
            }
            $slide->addMedia($this->file)->toMediaCollection('file');
            $slide = Slide::find($slide->id);
            $slotIndex = $this->getSlotIndex($slot->id);
            $this->form['time_slots'][$slotIndex]['slides'][] = $slide->toArray();
            $this->reset('file', 'fileSlot');
            $this->toastSuccess(__('Slide added successfully'));
        }
    }

    public function reorderSlides(array $slides): array
    {
        foreach ($slides as $i => $slide) {
            $slides[$i]['order'] = $i;
        }
        return $slides;
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
        $this->form['time_slots'][$slotPosition]['slides'] = $this->reorderSlides($slides);
        $this->toastSuccess("Slide order updated slotIndex: $slotPosition, slide from position: $oldPosition to: $newPosition", [
            'duration' => 15000
        ]);
    }

    public function removeSlide(int $slideId)
    {
        $slide = Slide::find($slideId);
        if (!$slide) {
            $this->toastError("Slide with id: {$slideId} not found!");
            return;
        }
        $slotId = $slide->time_slot_id;
        $slotIndex = collect($this->form['time_slots'])->search(fn($slot, $key) => $slot['id'] == $slotId);
        $slides = $this->form['time_slots'][$slotIndex]['slides'];
        $slideIndex = collect($slides)->search(fn($slide, $key) => $slide['id'] == $slideId);
        $delete = $slide->delete();
        if (!$delete) {
            $this->toastError(__('Delete slide id: :id failed!', ['id' => $slideId]));
        }
        unset($slides[$slideIndex]);
        $this->form['time_slots'][$slotIndex]['slides'] = $this->reorderSlides($slides);
        $this->toastSuccess(__("Slide removed slotIndex: :slot_index, slotId: :slot_id, slideIndex: :slide_index, slideId: :slide_id", [
            'slot_index' => $slotIndex,
            'slot_id' => $slotId,
            'slide_index' => $slideIndex,
            'slide_id' => $slideId,
        ]));
    }
    public function removeSlot(TimeSlot $slot)
    {
        // dd($slot);
        $slotId = $slot->id;
        $delete = $slot->delete();
        if ($delete) {
            $slotIndex = collect($this->form['time_slots'])->search(fn($slot, $key) => $slot['id'] == $slotId);
            unset($this->form['time_slots'][$slotIndex]);
            $this->toastSuccess(__("Time slot :slot_id removed", ['slot_id' => $slot->id]));
        } else {
            $this->toastError(__("Delete failed!"));
        }
    }
    public function resetScreen()
    {
        $this->form = $this->screen->toArray();
        $this->addSuccess("reset", "Reset done");
    }

    public function editSlide(Slide $slide)
    {
        $this->slideToEdit = $slide;
        $this->showSlideModal = true;
    }
    public function getSlideSlotIndex(int $slideId)
    {
        $slide = Slide::find($slideId);
        return $slide
            ? $this->getSlotIndex($slide->time_slot_id)
            : null;
    }
    public function getSlotIndex(int $slotId)
    {
        return collect($this->form['time_slots'])->search(fn($slot, $key) => $slot['id'] == $slotId);
    }

    #[Computed()]
    public function editSlideTitle(): bool
    {
        if (empty($this->slideToEdit)) {
            return '';
        }
        return __('Edit slide: :id', ['id' => $this->slideToEdit->id]);
    }

    #[Computed()]
    public function slideIsActive(): bool
    {
        if (empty($this->slideToEdit)) {
            return false;
        }
        $slotIndex = $this->getSlotIndex($this->slideToEdit->time_slot_id);
        $slides = $this->form['time_slots'][$slotIndex]['slides'];
        $slideIndex = collect($slides)->search(fn($slide, $key) => $slide['id'] == $this->slideToEdit->id);
        return boolval(data_get($this->form, "time_slots.{$slotIndex}.slides.{$slideIndex}.is_active"));
    }

    #[Computed()]
    public function slideInputKey(string $input): string
    {
        if (empty($this->slideToEdit)) {
            return '';
        }
        $slotId = $this->slideToEdit->time_slot_id;
        $slotIndex = collect($this->form['time_slots'])->search(fn($slot, $key) => $slot['id'] == $slotId);
        $slides = $this->form['time_slots'][$slotIndex]['slides'];
        $slideIndex = collect($slides)->search(fn($slide, $key) => $slide['id'] == $this->slideToEdit->id);
        return "form.time_slots.{$slotIndex}.slides.{$slideIndex}.{$input}";
    }

    #[Computed()]
    public function slideControlKey(int $slotIndex, int $slideIndex, string $input): string
    {
        if (empty($this->slideToEdit)) {
            return '';
        }
        return "form.time_slots.{$slotIndex}.slides.{$slideIndex}.{$input}";
    }
    public function save()
    {
        if (empty($this->form['slug'])) {
            $this->form['slug'] = Screen::generateSlug($this->form['name']);
        }
        // 1. Validate the entire form
        $validatedData = $this->validate();
        $formData = $validatedData['form'];

        // dd($formData);
        // 2. Update the Screen properties
        $this->screen->update([
            'name' => $formData['name'],
            'slug' => $formData['slug'],
            'is_active' => $formData['is_active'],
        ]);

        // 3. Update nested TimeSlots and Slides
        foreach ($formData['time_slots'] as $slotData) {
            $slot = TimeSlot::find($slotData['id']);

            if ($slot) {
                $slot->update([
                    'name' => $slotData['name'],
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'duration' => $slotData['duration'] ?? 5000, // Matching your migration default
                    'is_active' => $slotData['is_active'],
                ]);

                // 4. Update Slides within this slot
                if (isset($slotData['slides'])) {
                    foreach ($slotData['slides'] as $slideData) {
                        Slide::where('id', $slideData['id'])->update([
                            'name' => $slideData['name'],
                            'duration' => $slideData['duration'],
                            'transition' => $slideData['transition'],
                            'order' => $slideData['order'],
                            'is_active' => $slideData['is_active'],
                        ]);
                    }
                }
            }
        }

        $this->toastSuccess(__('Screen and all slots saved successfully'));
    }
};
