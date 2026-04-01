<div>
    <div class="mb-4">
        <button wire:confirm="{{ __('Are you sure?') }}" wire:click="restoreMenus" type="button"
            class="btn btn-red btn-xs pill">
            <i class="icon bi-arrow-counterclockwise"></i>
            <span wire:loading.remove wire:target="restoreMenus">{{ __('Restore defaults') }}</span>
            <fgx:loader wire:loading wire:target="restoreMenus" />
        </button>
    </div>

    <fgx:status class="alert-soft xs mt-2" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="col">
            <livewire:dashboard::menus.create />
        </div>
        <div class="col">
            <livewire:dashboard::menus.select
                wire:model.live="current_menu_id" />
        </div>
    </div>
    @if ($menu)
        <livewire:dashboard::menus.edit
            :menu="$menu"
            :key="$menu?->id" class="mt-6" />
        <div class="grid md:grid-cols-3 gap-4 mt-4">
            <div class="col">
                <livewire:dashboard::menus.add
                    :menu="$menu"
                    :key="'add-' . $menu?->id ?? 'null'" />
            </div>
            <div class="col md:col-span-2">
                <livewire:dashboard::menus.structure
                    :menu="$menu"
                    :key="'structure-' . $menu?->id ?? 'null'" />
            </div>
        </div>
    @else
        <fgx:alert soft class="mt-6" :content="__('No menu selected')" />
    @endif
</div>
