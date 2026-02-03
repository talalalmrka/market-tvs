<div>
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
        @include('dashboard.screens.edit.slide-modal')

        <pre>
            @php
                print_r($form);
            @endphp
        </pre>

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
