<div x-data="TimeSlotCollapse({{ $slot['id'] }})" wire:key="{{ $slot['id'] }}" class="border mb-4 rounded-lg shadow-sm"
    :class="{ 'ring-2 ring-primary/50': open }">
    <div class="flex items-center gap-2 p-2" :class="{ 'border-b': open }">
        <h5 x-on:click="toggle"
            class="mb-0 flex items-center gap-2 flex-1 cursor-pointer">
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
    <div x-show="open" class="p-2">
        <div class="grid grid-cols-1 gap-3">
            <div class="col">
                <fgx:input wire:model.live="form.time_slots.{{ $slotIndex }}.name"
                    id="form.time_slots.{{ $slotIndex }}.name" :label="__('Name')" />
            </div>
            <div class="col">
                <fgx:input type="time"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.start_time"
                    id="form.time_slots.{{ $slotIndex }}.start_time"
                    :label="__('Start time')" :info="$slot['start_time']" />
            </div>
            <div class="col">
                <fgx:input type="time"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.end_time"
                    id="form.time_slots.{{ $slotIndex }}.end_time"
                    :label="__('End time')"
                    :info="$slot['end_time']" />
            </div>
            <div class="col">
                <fgx:input type="number"
                    wire:model.live="form.time_slots.{{ $slotIndex }}.duration"
                    id="form.time_slots.{{ $slotIndex }}.duration"
                    :label="__('Slide duration (ms)')" />
            </div>
            <div class="col">
                <fgx:switch
                    wire:model.live="form.time_slots.{{ $slotIndex }}.is_active"
                    id="form.time_slots.{{ $slotIndex }}.is_active"
                    :label="__('Active')" checked="{{ boolval($slot['is_active']) }}" />
            </div>
            <div class="col">
                <fgx:label :label="__('Slides')" />
                <div class="form-control border-2 border-dashed p-1 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4"
                        wire:sort="sortItem"
                        wire:sort:group="group-{{ $slot['id'] }}">
                        @foreach ($slot['slides'] as $slideIndex => $slide)
                            @include('dashboard.screens.edit.slide')
                        @endforeach
                        <label wire:cloak for="file-{{ $slot['id'] }}"
                            x-data="UploadSlide({ slotId: @js($slot['id']) })"
                            class="col aspect-video flex flex-col items-center justify-center relative border rounded-lg overflow-hidden cursor-pointer bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                            <div x-show="!uploading" class="flex flex-col items-center justify-center">
                                <i class="icon bi-cloud-upload text-5xl"></i>
                                <div class="text-xs text-muted">
                                    {{ __('Upload image or video') }}
                                </div>
                            </div>
                            <input type="file" id="file-{{ $slot['id'] }}"
                                class="hidden" x-bind="fileInput"
                                accept="image/*, video/*">
                            <div x-bind="progressContainer"
                                class="w-full absolute top-1/2 -translate-y-1/2 start-1/2 -translate-x-1/2 flex items-center gap-2 px-2">
                                <div
                                    class="progress lg flex-1"
                                    role="progressbar">
                                    <div class="progress-bar" :style="`width: ${progress}%`" x-text="`${progress}%`">
                                    </div>
                                </div>
                                <button type="button" x-bind="buttonCancelUpload">
                                    <i class="icon bi-x-lg"></i>
                                </button>
                            </div>
                        </label>
                    </div>
                </div>
                <x-fgx::error id="file" />
            </div>
        </div>

    </div>
</div>
