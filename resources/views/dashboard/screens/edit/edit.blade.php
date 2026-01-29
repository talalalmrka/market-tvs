<div>
    <h3>{{ $title }}</h3>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4">
                    <div class="col">
                        <fgx:input wire:model.live="form.name" id="form.name" :label="__('Name')" />
                    </div>
                    <div class="col">
                        <fgx:label :label="__('Time slots')" />
                        @foreach ($form['time_slots'] as $i => $slot)
                            <div class="card mb-4" x-data="{ open: true }">
                                <div class="card-header flex items-center gap-2">
                                    <h5 class="card-title flex-1">{{ $slot['name'] }}</h5>
                                    <button type="button" x-on:click="open = !open">
                                        <i class="icon bi-chevron-down" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                </div>
                                <div x-collapse x-show="open" class="card-body">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div class="col">
                                            <fgx:input wire:model.live="form.time_slots.{{ $i }}.name"
                                                id="form.time_slots.{{ $i }}.name" :label="__('Name')" />
                                        </div>
                                        <div class="col">
                                            <fgx:input type="time"
                                                wire:model.live="form.time_slots.{{ $i }}.start_time"
                                                id="form.time_slots.{{ $i }}.start_time"
                                                :label="__('Start time')" />
                                        </div>
                                        <div class="col">
                                            <fgx:input type="time"
                                                wire:model.live="form.time_slots.{{ $i }}.end_time"
                                                id="form.time_slots.{{ $i }}.end_time"
                                                :label="__('End time')" />
                                        </div>
                                        <div class="col">
                                            <fgx:input type="number"
                                                wire:model.live="form.time_slots.{{ $i }}.slide_duration"
                                                id="form.time_slots.{{ $i }}.slide_duration"
                                                :label="__('Slide duration (ms)')" />
                                        </div>
                                        <div class="col">
                                            <fgx:input type="number"
                                                wire:model.live="form.time_slots.{{ $i }}.priority"
                                                id="form.time_slots.{{ $i }}.priority"
                                                :label="__('Priority')" />
                                        </div>
                                        <div class="col">
                                            <fgx:switch
                                                wire:model.live="form.time_slots.{{ $i }}.is_active"
                                                id="form.time_slots.{{ $i }}.is_active"
                                                :label="__('Active')" checked="{{ boolval($slot['is_active']) }}" />
                                        </div>
                                        <div class="col">
                                            <fgx:label :label="__('Slides')" />
                                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"
                                                wire:sort="sortItem"
                                                wire:sort:group="group-{{ $slot['id'] }}">
                                                @foreach ($slot['slides'] as $slide)
                                                    <div class="col relative border shadow rounded-2xl overflow-hidden"
                                                        wire:sort:item="{{ $slide['id'] }}">
                                                        <img src="{{ $slide['url'] }}"
                                                            class="w-full h-full object-cover"
                                                            alt="{{ $slide['id'] }}">
                                                        <div
                                                            class="absolute bottom-0 w-full bg-black/15 text-white text-xs p-1.5 text-center">
                                                            Id: {{ $slide['id'] }}, Order: {{ $slide['order'] }}
                                                        </div>
                                                        <div
                                                            class="absolute top-0 w-full text-shadow text-white text-xs p-1.5 flex items-center justify-between">
                                                            <button wire:sort:handle type="button"
                                                                class="cursor-move text-2xl"
                                                                title="{{ __('Move') }}">
                                                                <i class="icon bi-list"></i>
                                                            </button>
                                                            <div class="flex items-center gap-2 justify-end">
                                                                <button wire:click="editSlide({{ $slide['id'] }})"
                                                                    wire:sort:ignore type="button"
                                                                    class=""
                                                                    title="{{ __('Edit') }}">
                                                                    <i class="icon bi-pencil-square"></i>
                                                                </button>
                                                                <button wire:click="removeSlide({{ $slide['id'] }})"
                                                                    wire:sort:ignore type="button"
                                                                    class=""
                                                                    title="{{ __('Delete') }}">
                                                                    <i class="icon bi-trash-fill"></i>
                                                                </button>
                                                            </div>
                                                        </div>


                                                    </div>
                                                @endforeach
                                            </div>
                                            <fgx:status key="slot-{{ $slot['id'] }}" class="mt-4" />
                                        </div>
                                    </div>
                                    @dump($slot)
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer flex items-center justify-between">
                <button type="submit" class="btn btn-primary">
                    <i class="icon bi-floppy"></i>
                    <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                    <fgx:loader wire:loading wire:target="save" />
                </button>
                <fgx:status id="save" class="xs alert-soft" />
            </div>
        </div>

        <fgx:card>
            @dump($form)
        </fgx:card>
    </form>
</div>
