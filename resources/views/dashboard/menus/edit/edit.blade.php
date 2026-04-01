<form wire:submit="save">
    <div x-data="{
        open: false,
        toggle() {
            this.open = !this.open;
            localStorage.setItem('edit-menu-collapse', this.open ? 'true' : 'false');
        },
        init() {
            this.open = localStorage.getItem('edit-menu-collapse') == 'true';
        },
    }" {{ $attributes->merge([
        'class' => css_classes(['card']),
    ]) }}>
        <div x-on:click="toggle" class="card-header select-none cursor-pointer flex items-center gap-2"
            :class="{ 'border-b-none': !open }">
            <div class="card-title text-primary flex-space-2 select-none flex-1">
                <i class="icon bi-gear-wide-connected"></i>
                <span>{{ __('Settings (:menu)', ['menu' => $menu->name]) }}</span>
            </div>
            <i class="icon bi-chevron-down" :class="{ 'rotate-180': open }"></i>
        </div>
        <div x-show="open" class="card-body">
            <div class="grid grid-cols-1 gap-4">
                <div class="col">
                    <fgx:input id="name" class="sm" wire:model.live="name" :label="__('Name')" />
                </div>
                <div class="col">
                    <fgx:select id="position" class="sm" wire:model.live="position"
                        :label="__('Position')"
                        :options="menu_position_options(__('Select position'))" />
                </div>
                <div class="col">
                    <fgx:input id="class_name" class="sm" wire:model.live="class_name"
                        :label="__('CSS Class')"
                        placeholder="{{ __('e.g. my-custom-menu') }}" />
                </div>
            </div>
        </div>
        <div x-show="open" class="card-footer flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="icon bi-floppy"></i>
                    <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                    <fgx:loader wire:loading wire:target="save" />
                </button>
                <fgx:status soft size="xs" id="save" class="p-0 bg-transparent border-0" />
            </div>
            <div class="flex items-center gap-2">
                <fgx:status soft size="xs" id="delete" class="p-0 bg-transparent border-0" />
                <button wire:click="delete" type="button" class="btn btn-red btn-sm">
                    <i class="icon bi-trash-fill"></i>
                    <span wire:loading.remove wire:target="delete">{{ __('Delete') }}</span>
                    <fgx:loader wire:loading wire:target="delete" />
                </button>
            </div>
        </div>
    </div>

</form>
