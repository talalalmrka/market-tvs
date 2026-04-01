<div {{ $attributes->merge([
    'class' => css_classes(['card overflow-visible']),
]) }}>
    <div class="card-header">
        <div class="card-title text-primary flex-space-2">
            <i class="icon bi-list"></i>
            <span>{{ __('Select menu') }}</span>
        </div>
    </div>
    <div class="card-body">
        <div wire:cloak x-data="{ open: false }" class="relative" x-on:click.away="open = false">
            <div class="form-control-container">
                <div class="form-control sm flex items-center cursor-pointer">
                    <div x-on:click="open = !open" class="flex-1 truncate">{{ $this->selectedLabel }}</div>
                    <div class="flex items-center gap-2">
                        <button wire:show="value" wire:click="$set('value', null)" type="button"
                            title="{{ __('Clear') }}">
                            <i class="icon bi-x-lg"></i>
                        </button>
                        <i class="icon bi-chevron-expand"></i>
                    </div>
                </div>
            </div>
            <div x-collapse x-show="open"
                class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto focus:outline-none p-0 list-none text-sm">
                <div class="px-3 py-2">
                    <div class="form-control-container">
                        <span class="start-icon">
                            <i class="icon bi-search"></i>
                        </span>
                        <input class="xs pill form-control has-start-icon" type="search" wire:model.live="search"
                            placeholder="{{ __('Search...') }}">
                    </div>
                </div>
                @forelse ($this->options as $option)
                    <button
                        type="button"
                        wire:click="$set('value', {{ $option->value }})"
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
                    <div class="alert alert-info alert-soft alert-sm m-2">
                        {{ __('No items') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
