<div
    {{ $attributes }}>
    <x-card :title="__('Select menu')">
        <fgx:select
            id="menuId"
            wire:model.live="menuId"
            :options="menu_options(__('Select menu'))"
            class="sm" />
    </x-card>
    @if ($menu)
        <div x-data="MySortGroup" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="col">
                <x-card :title="__('Log')">
                    <pre x-bind="log" class="max-h-screen overflow-auto"></pre>
                </x-card>

            </div>
            <div class="col md:col-span-2">
                <x-card :title="__('Structure edited (:name)', ['name' => $menu->name])">
                    <form wire:submit="save">
                        {{-- <x-sort-group x-data="{ parentId: null, path: 'items' }" /> --}}
                        <div x-ref="rootGroup"></div>
                        <div class="card-footer flex items-center gap-2 justify-between">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="icon bi-floppy"></i>
                                <span wire:loading.remove wire:target="save">{{ __('Save changes') }}</span>
                                <fgx:loader wire:loading wire:target="save" />
                            </button>
                            <fgx:status id="save" class="alert-soft xs" />
                        </div>
                    </form>
                </x-card>
            </div>
        </div>
    @else
        <x-alert xs soft class="mt-6">{{ __('No menu selected') }}</x-alert>
    @endif

</div>
