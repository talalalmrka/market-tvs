<div wire:cloak wire:show="showSlideModal" x-on:click="$wire.showSlideModal = false" class="modal-backdrop show"></div>
<div wire:cloak wire:show="showSlideModal" id="edit-slide-{{ $slideToEdit?->id }}" class="modal fade show">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $this->editSlideTitle }}</h5>
                <button x-on:click="$wire.showSlideModal = false" type="button" class="btn-close">
                    <i class="icon bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="col md:col-span-2">
                        <img src="{{ $slideToEdit?->url }}" class="w-full" alt="">
                    </div>
                    <div class="col">
                        <div class="grid grid-cols-1 gap-3">
                            <div class="col">
                                <fgx:input
                                    wire:model.live="{{ $this->slideInputKey('name') }}"
                                    id="{{ $this->slideInputKey('name') }}"
                                    :label="__('Name')" />
                            </div>
                            <div class="col">
                                <fgx:input type="number"
                                    wire:model.live="{{ $this->slideInputKey('duration') }}"
                                    id="{{ $this->slideInputKey('duration') }}"
                                    :label="__('Duration')" />
                            </div>
                            <div class="col">
                                <fgx:select
                                    id="{{ $this->slideInputKey('transition') }}"
                                    wire:model.live="{{ $this->slideInputKey('transition') }}"
                                    :options="slide_transition_options()"
                                    :label="__('Transition')" />

                            </div>
                            <div class="col">
                                <fgx:switch
                                    wire:model.live="{{ $this->slideInputKey('is_active') }}"
                                    id="{{ $this->slideInputKey('is_active') }}"
                                    :label="__('Active')" checked="{{ $this->slideIsActive }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button x-on:click="$wire.showSlideModal = false" type="button"
                    class="btn btn-primary">{{ __('Done') }}</button>
            </div>
        </div>
    </div>
</div>
