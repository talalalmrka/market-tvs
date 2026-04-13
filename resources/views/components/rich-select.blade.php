@props([
    'id' => uniqid('select-'),
    'icon' => null,
    'label' => null,
    'value' => null,
    'required' => false,
    'error' => null,
    'disabled' => false,
    'atts' => [],
    'startIcon' => null,
    'endIcon' => null,
    'startView' => null,
    'endView' => null,
    'info' => null,
    'container_class' => null,
    'container_atts' => [],
    'options' => [],
    'class' => null,
    'multiple' => false,
])

@php
    $error = $error ?? $errors->has($id);

    $model = $attributes->whereStartsWith('wire:model')->first();
    $modelName = $model ? explode(':', $model)[0] : null;

    $selectedValue = old($id, $value);

    if ($multiple && !is_array($selectedValue)) {
        $selectedValue = $selectedValue ? (array) $selectedValue : [];
    }
@endphp

<fgx:label for="{{ $id }}" :icon="$icon" :required="$required" :label="$label" />

<div
    x-data="RichSelect(@js($model))"
    x-cloak
    x-on:click.outside="closeDropdown()"
    {{ attributes(
        array_merge(
            [
                'class' => css_classes(['form-control-container', $container_class => $container_class]),
            ],
            $container_atts,
        ),
    ) }}>
    @if ($startIcon || $startView)
        <span class="start-icon">
            @if (!empty($startView))
                {!! $startView !!}
            @endif
            @icon($startIcon)
        </span>
    @endif
    <select {!! $attributes->merge([
        ...[
            'id' => $id,
            'x-ref' => 'hiddenSelect',
            'disabled' => $disabled ? '' : null,
            'class' => 'hidden',
        ],
        ...$atts,
    ]) !!}>
        @foreach ($options as $option)
            <option value="{{ data_get($option, 'value') }}">{!! data_get($option, 'label') !!}</option>
        @endforeach
    </select>

    {{-- Main input view --}}
    <div
        x-on:click="open ? closeDropdown() : openDropdown()"
        x-on:keydown.arrow-down.prevent="moveDown()"
        x-on:keydown.arrow-up.prevent="moveUp()"
        x-on:keydown.enter.prevent="selectHighlighted()"
        x-on:keydown.escape.prevent="closeDropdown()"
        class="{{ css_classes([
            'form-select flex items-center justify-between cursor-pointer select-none',
            $class => $class,
            'error' => $error,
            'has-start-icon' => !empty($startIcon) || !empty($startView),
            'has-end-icon' => !empty($endIcon) || !empty($endView),
        ]) }}"
        {{ $disabled ? 'disabled' : '' }}>
        <span class="truncate" x-text="selectedLabel"></span>
        <button x-on:click="clear" type="button" title="{{ __('Clear') }}">
            <i class="icon bi-x-lg"></i>
        </button>
    </div>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-transition
        class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden"
        style="display:none;">
        <div class="p-2 border-b">
            <input
                type="search"
                x-ref="searchInput"
                x-model="search"
                placeholder="Search..."
                class="form-control xs pill" />
        </div>

        <div class="max-h-60 overflow-auto">
            <template x-if="filteredOptions.length === 0">
                <div class="p-3 text-sm opacity-60">No results found</div>
            </template>

            <template x-for="(opt, index) in filteredOptions" :key="opt.value">
                <button
                    type="button"
                    x-on:click="toggleOption(opt.value)"
                    x-bind:class="{
                        'bg-gray-100': highlighted === index,
                        'font-semibold bg-primary text-bg-primary': isSelected(opt.value)
                    }"
                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                    <span x-html="opt.label"></span>

                    <template x-if="isSelected(opt.value)">
                        <i class="icon bi-check"></i>
                    </template>
                </button>
            </template>
        </div>

        <template x-if="multiple && selected.length">
            <div class="p-2 border-t">
                <button type="button" class="text-sm text-red-600" x-on:click="clear()">
                    Clear
                </button>
            </div>
        </template>
    </div>

    @if ($endIcon || $endView)
        <span class="end-icon">
            @icon($endIcon)
            @if (!empty($endView))
                {!! $endView !!}
            @endif
        </span>
    @endif
</div>

<fgx:info :id="$id" :info="$info" />
<fgx:error :id="$id" />
