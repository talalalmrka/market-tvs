<div class="grid grid-cols-1 gap-3">
    <div class="col">
        <fgx:select id="value.type" wire:model.live="value.type" :label="__('Type')" class="{{ $class }}"
            :options="menu_item_type_options()" />
    </div>
    @if (data_get($value, 'type') === 'custom')
        <div class="col">
            <fgx:input id="value.url" wire:model.live="value.url"
                class="{{ $class }}"
                :label="__('Url')" />
        </div>
    @else
        <div class="col">
            <div wire:cloak x-data="{ open: false }" class="relative" x-on:click.away="open = false">
                <fgx:label x-on:click="open = !open" for="value.model_id" :icon="$icon" :required="$required"
                    :label="$this->selectLabel" />
                <div class="{{ css_classes(['form-control-container', $container_class]) }}">
                    <div
                        {{ $attributes->whereDoesntStartWith('wire:model')->merge([
                            'class' => css_classes(['form-control flex items-center cursor-pointer', $size, $class]),
                        ]) }}>
                        <div x-on:click="open = !open" class="flex-1 truncate">{{ $this->selectedLabel }}</div>
                        <div class="flex items-center gap-2">
                            <button wire:show="value" wire:click="$set('value.model_id', null)" type="button"
                                title="{{ __('Clear') }}">
                                <i class="icon bi-x-lg"></i>
                            </button>
                            <i class="icon bi-chevron-expand"></i>
                        </div>
                    </div>
                </div>
                <div x-collapse x-show="open"
                    class="{{ css_classes([
                        'absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto focus:outline-none p-0 list-none text-sm',
                        $dropdown_class,
                    ]) }}">
                    <div class="px-3 py-2">
                        <div class="form-control-container">
                            <span class="start-icon">
                                <i class="icon bi-search"></i>
                            </span>
                            <input class="xs pill form-control has-start-icon" type="search" wire:model.live="search"
                                placeholder="{{ __('Search...') }}">
                            <fgx:info :info="$search" />
                        </div>
                    </div>
                    @forelse ($this->options as $option)
                        <button
                            type="button"
                            wire:click="$set('value.model_id', {{ $option->value }})"
                            x-on:click="open = false"
                            class="w-full text-sm px-3 py-2 cursor-pointer flex items-center gap-2 hover:bg-primary/10 dark:hover:bg-primary/10 disabled:opacity-50 disabled:cursor-not-allowed [.active]:bg-primary [.active]:text-white dark:[.active]:bg-primary-500 [.active]:hover:bg-primary-600 dark:hover:[.active]:bg-primary-600"
                            title="{{ $option->label }}"
                            :class="{ 'active': @js($option->selected) }">
                            <span class="flex-1 text-start truncate">
                                {{ $option->label }}
                            </span>
                            <span x-show="@js($option->selected)">
                                <i class="icon bi-check"></i>
                            </span>
                        </button>
                    @empty
                        <x-alert xs soft class="m-2">
                            {{ __('No items') }}
                        </x-alert>
                    @endforelse
                </div>
                <fgx:info id="value.model_id" :info="$info" />
                <fgx:error id="value.model_id" />
            </div>
        </div>
    @endif
</div>
