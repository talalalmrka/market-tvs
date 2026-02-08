<div wire:cloak id="edit-slide-modal-{{ $slide['id'] }}"
    x-bind="slideModal({{ $slide['id'] }})">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ edit_slide_modal_title($slot, $slide) }}
                </h5>
                <button type="button" class="btn-close" x-bind="slideModalToggler({{ $slide['id'] }})">
                    <i class="icon bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="col md:col-span-2">
                        @if ($slide['type'] == 'image')
                            <img src="{{ $slide['url'] }}" class="w-full" alt="{{ $slide['name'] }}">
                        @elseif ($slide['type'] == 'video')
                            <video
                                class="w-full h-auto"
                                muted
                                playsinline
                                controls>
                                <source src="{{ $slide['url'] }}" />
                            </video>
                        @endif
                    </div>
                    <div class="col">
                        <div class="grid grid-cols-1 gap-3">
                            <div class="col">
                                <fgx:input
                                    wire:model.live="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.name"
                                    id="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.name"
                                    :label="__('Name')" />
                            </div>
                            <div class="col">
                                <fgx:input type="number"
                                    wire:model.live="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.duration"
                                    id="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.duration"
                                    :label="__('Duration')" />
                            </div>
                            <div class="col">
                                <fgx:select
                                    id="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.transition"
                                    wire:model.live="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.transition"
                                    :options="slide_transition_options()"
                                    :label="__('Transition')" />

                            </div>
                            <div class="col">
                                <fgx:switch
                                    wire:model.live="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.is_active"
                                    id="form.time_slots.{{ $slotIndex }}.slides.{{ $slideIndex }}.is_active"
                                    :label="__('Active')" checked="{{ boolval($slide['is_active']) }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-primary"
                    x-bind="slideModalToggler({{ $slide['id'] }})">{{ __('Done') }}</button>
            </div>
        </div>
    </div>
</div>
