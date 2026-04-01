@props(['items', 'parentId', 'inputKey'])

<div
    wire:sort="sortMenu"
    wire:sort:group="items"
    wire:sort:group-id="{{ $parentId }}"
    {{ $attributes->merge([
        'class' => '',
    ]) }}>
    @foreach ($items as $i => $item)
        @php
            $model_key = "$inputKey.{$i}";
            $itemId = data_get($item, 'id');
            $itemOrder = data_get($item, 'order');
            $itemIcon = data_get($item, 'icon');
            $itemModelType = data_get($item, 'model_type');
            $itemType = data_get($item, 'type');
            $itemParentId = data_get($item, 'parent_id');
            $itemParentName = empty($itemParentId) ? __('Top') : data_get($item, 'parent_name');
            $children = data_get($item, 'children', []);
        @endphp
        <div
            id="sort-item-{{ $itemId }}"
            wire:key="{{ $itemId }}"
            wire:sort:item="{{ $itemId }}">
            <div
                x-cloak
                data-id="{{ $itemId }}"
                class="menu-structure-accordion-item border text-sm rounded-lg shadow-sm"
                :class="{ 'ring-2 ring-primary/50': isOpen(@js($itemId)) }">
                <div class="flex items-center gap-2 p-2" :class="{ 'border-b': isOpen(@js($itemId)) }">
                    <button wire:sort:handle type="button"
                        class="cursor-move"
                        title="{{ __('Move') }}">
                        <i class="icon bi-arrows-move"></i>
                    </button>
                    <div wire:sort:ignore
                        x-on:click="toggle(@js($itemId))"
                        class="flex-1 flex items-center gap-2 select-none cursor-pointer">
                        <div
                            class="flex-1 flex items-center gap-2">
                            @if (!empty($itemIcon))
                                <i class="icon {{ $itemIcon }}"></i>
                            @endif
                            <span class="font-semibold">{{ data_get($item, 'name') }}</span>
                            <span class="badge badge-primary pill flex items-center font-medium gap-0.5">
                                <i class="icon bi-hash"></i>
                                <span>{{ $itemId }}</span>
                            </span>
                            <span class="badge badge-teal pill flex items-center gap-0.5">
                                <i class="icon bi-sort-alpha-down"></i>
                                <span>{{ $itemOrder }}</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                                <i class="icon bi-arrow-90deg-up"></i>
                                <span class="parent-span">{{ $itemParentName }}</span>
                            </span>
                            <span class="badge badge-teal pill">{{ data_get($item, 'type') }}</span>
                            <i class="icon bi-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </div>
                    <button wire:click="delete({{ $itemId }})" type="button" title="{{ __('Delete') }}">
                        <i wire:loading.remove wire:target="delete({{ $itemId }})"
                            class="icon bi-trash-fill"></i>
                        <fgx:loader wire:loading wire:target="delete({{ $itemId }})" />
                    </button>
                </div>
                <div x-show="isOpen(@js($itemId))" class="p-2">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="col">
                            <fgx:input wire:model.live="{{ $model_key }}.name" class="sm"
                                id="{{ $model_key }}.name" :label="__('Name')" />
                        </div>
                        <div class="col">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="col">
                                    <fgx:icon-picker id="{{ $model_key }}.icon"
                                        wire:model.live="{{ $model_key }}.icon" :label="__('Icon')"
                                        :value="data_get($item, 'icon')" size="sm" />
                                </div>
                                <div class="col">
                                    <fgx:input wire:model.live="{{ $model_key }}.title" class="sm"
                                        id="{{ $model_key }}.title" :label="__('Title')" />
                                </div>
                                <div class="col">
                                    <fgx:input wire:model.live="{{ $model_key }}.class_name"
                                        class="sm"
                                        id="{{ $model_key }}.class_name" :label="__('CSS Class')" />
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <livewire:components::select-permalink
                                wire:model.live="{{ $model_key }}" class="sm" />
                        </div>
                        <div class="col">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="col">
                                    <fgx:switch id="{{ $model_key }}.new_tab"
                                        wire:model.live="{{ $model_key }}.new_tab"
                                        :label="__('Open in new tab')"
                                        :checked="boolval(data_get($item, 'new_tab'))" />
                                </div>
                                <div class="col">
                                    <fgx:switch id="{{ $model_key }}.navigate"
                                        wire:model.live="{{ $model_key }}.navigate"
                                        :label="__('Wire navigate')"
                                        :checked="boolval(data_get($item, 'navigate'))" />
                                </div>
                                <div class="col">
                                    <button wire:click="deleteItem({{ $itemId }})" type="button"
                                        class="btn btn-outline-red btn-sm">
                                        <i class="icon bi-trash-fill"></i>
                                        <span wire:loading.remove
                                            wire:target="delete({{ $itemId }})">{{ __('Remove') }}</span>
                                        <fgx:loader wire:loading wire:target="delete({{ $itemId }})" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-menu-tree
                :items="$children"
                :parent-id="$itemId"
                input-key="{{ $model_key }}.children"
                class="ms-4" />
            <div wire:click="setParent({{ $parentId }})"
                class="cursor-pointer text-center group">
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <hr class="border-2 border-primary flex-1">
                    <i class="icon fg-plus text-primary"></i>
                    <hr class="border-2 border-primary flex-1">
                </div>
            </div>
        </div>
    @endforeach
</div>
