<div x-data="TimeSlotCollapse({ slotId: @js($slot['id']) })" wire:key="{{ $slot['id'] }}" class="card mb-4 shadow-none">
    <div class="card-header flex items-center gap-2" :class="{ 'border-b-0': !open }">
        <h5 x-on:click="toggle"
            class="card-title flex items-center gap-2 flex-1 cursor-pointer">
            <i class="icon bi-chevron-down transition-transform"
                :class="{ 'rotate-180': open }"></i>
            <span>{{ data_get($slot, 'name') }}</span>
        </h5>
        <button wire:click="removeSlot({{ $slot['id'] }})" type="button" title="Remove">
            <i wire:loading.remove wire:target="removeSlot({{ $slot['id'] }})"
                class="icon bi-trash-fill"></i>
            <fgx:loader wire:loading wire:target="removeSlot({{ $slot['id'] }})" />
        </button>
    </div>
    <div x-collapse x-show="open" class="card-body">
        <div class="grid grid-cols-1 gap-3">
            <div class="col">
                <fgx:input wire:model.live="form.time_slots.{{ $slotIndex }}.name"
                    id="form.time_slots.{{ $slotIndex }}.name" :label="__('Name')" />
            </div>
            <div class="col">
                <fgx:input type="time"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.start_time"
                    id="form.time_slots.{{ $slotIndex }}.start_time"
                    :label="__('Start time')" />
            </div>
            <div class="col">
                <fgx:input type="time"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.end_time"
                    id="form.time_slots.{{ $slotIndex }}.end_time"
                    :label="__('End time')" />
            </div>
            <div class="col">
                <fgx:input type="number"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.slide_duration"
                    id="form.time_slots.{{ $slotIndex }}.slide_duration"
                    :label="__('Slide duration (ms)')" />
            </div>
            <div class="col">
                <fgx:input type="number"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.priority"
                    id="form.time_slots.{{ $slotIndex }}.priority"
                    :label="__('Priority')" />
            </div>
            <div class="col">
                <fgx:switch
                    wire:model.live="form.time_slots.{{ $slotIndex }}.is_active"
                    id="form.time_slots.{{ $slotIndex }}.is_active"
                    :label="__('Active')" checked="{{ boolval($slot['is_active']) }}" />
            </div>
            <div class="col">
                <fgx:label :label="__('Slides')" />
                <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4"
                    wire:sort="sortItem"
                    wire:sort:group="group-{{ $slot['id'] }}">
                    @foreach ($slot['slides'] as $slideIndex => $slide)
                        @include('dashboard.screens.edit.slide')
                    @endforeach
                    <label for="form.time_slots.{{ $slotIndex }}.file" wire:key="add-slide-{{ $slot['id'] }}"
                        class="col aspect-video relative flex flex-col items-center justify-center border hover:shadow rounded-lg overflow-hidden cursor-pointer bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                        <i class="icon bi-cloud-upload text-5xl"></i>
                        <div class="text-xs text-muted">
                            {{ __('Upload image or video') }}
                        </div>
                        <input type="file" id="form.time_slots.{{ $slotIndex }}.file"
                            wire:model.live="form.time_slots.{{ $slotIndex }}.file" class="hidden">
                    </label>
                </div>
                <fgx:status key="slot-{{ $slot['id'] }}" class="mt-4" />
            </div>
        </div>

    </div>
</div>
