<div x-data="{
    slideId: null,
    toggleSlideModal(id) {
        this.slideId = (this.slideId === id) ? null : id;
    },
    closeModal() {
        this.slideId = null;
    },
    slideModalToggler(id) {
        return {
            ['@click']() {
                this.toggleSlideModal(id);
            },
        };
    },
    slideModalShown(id) {
        return this.slideId === id;
    },
    slideModal(id) {
        return {
            [':class']() {
                return {
                    'modal': true,
                    'fade': true,
                    'show': this.slideId === id,
                };
            },
        };
    },
    backDrop: {
        ['@click']() {
            this.closeModal();
        },
        [':class']() {
            return {
                'modal-backdrop': true,
                'show': this.slideId !== null
            };
        },
    },
}">
    <form wire:submit="save" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4">
                    <div class="col">
                        <fgx:input wire:model.live="form.name" id="form.name" :label="__('Name')" />
                    </div>
                    <div class="col">
                        <fgx:input wire:model.live="form.slug" id="form.slug" :label="__('Slug')" />
                    </div>
                    <div class="col">
                        <fgx:switch wire:model.live="form.is_active" id="form.is_active" :label="__('Active')"
                            :checked="boolval($form['is_active'])" />
                    </div>
                    <div class="col">
                        <fgx:label :label="__('Time slots')" />
                        @forelse ($form['time_slots'] as $slotIndex => $slot)
                            @include('dashboard.screens.edit.time-slot')
                        @empty
                            <fgx:alert soft :content="__('This screen hasen\'t time slots yet!')" />
                        @endforelse

                        <button wire:click="addSlot" type="button" class="btn btn-primary">
                            <i class="icon fg-plus"></i>
                            <span wire:loading.remove wire:target="addSlot">{{ __('Add Time Slot') }}</span>
                            <fgx:loader wire:loading wire:target="addSlot" />
                        </button>
                    </div>
                </div>
                <fgx:status id="save" class="xs alert-soft mt-3" />
                <fgx:status id="reset" class="xs alert-soft mt-3" />
            </div>
        </div>
        <div x-bind="backDrop"></div>
        @foreach ($form['time_slots'] as $slotIndex => $slot)
            @foreach ($slot['slides'] as $slideIndex => $slide)
                @include('dashboard.screens.edit.edit-slide')
            @endforeach
        @endforeach
        @if ($errors->any())
            <div class="alert alert-soft-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="max-h-82 overflow-auto text-xs">
            @pre($form)
        </div>

        <div class="sticky bottom-0 flex items-center justify-between bg-body-bg dark:bg-body-bg-dark p-3 z-2">
            <button type="submit" class="btn btn-primary">
                <i class="icon bi-floppy"></i>
                <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                <fgx:loader wire:loading wire:target="save" />
            </button>
            <button wire:click="resetScreen" type="button" class="btn btn-secondary">
                <i class="icon bi-arrow-clockwise"></i>
                <span wire:loading.remove wire:target="resetScreen">{{ __('Reset') }}</span>
                <fgx:loader wire:loading wire:target="resetScreen" />
            </button>
        </div>
    </form>
</div>
