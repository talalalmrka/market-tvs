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
    x-data="{
        open: false,
        search: '',
        highlighted: 0,
        multiple: @js($multiple),
        options: @js($options),
        selected: @js($selectedValue),

        get filteredOptions() {
            if (!this.search) return this.options;

            return this.options.filter(opt =>
                (opt.label ?? '').toLowerCase().includes(this.search.toLowerCase())
            );
        },

        get selectedLabel() {
            if (this.multiple) {
                let selectedOptions = this.options.filter(o => this.selected.includes(o.value));
                if (!selectedOptions.length) return 'Select...';
                return selectedOptions.map(o => o.label).join(', ');
            }

            let found = this.options.find(o => o.value == this.selected);
            return found ? found.label : 'Select...';
        },

        isSelected(value) {
            if (this.multiple) return this.selected.includes(value);
            return this.selected == value;
        },

        toggleOption(value) {
            if (this.multiple) {
                if (this.selected.includes(value)) {
                    this.selected = this.selected.filter(v => v !== value);
                } else {
                    this.selected.push(value);
                }
            } else {
                this.selected = value;
                this.open = false;
            }
        },

        clear() {
            this.selected = this.multiple ? [] : null;
        },

        focusSearch() {
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        openDropdown() {
            if (@js($disabled)) return;
            this.open = true;
            this.highlighted = 0;
            this.focusSearch();
        },

        closeDropdown() {
            this.open = false;
            this.search = '';
            this.highlighted = 0;
        },

        moveDown() {
            if (!this.open) return this.openDropdown();
            if (this.highlighted < this.filteredOptions.length - 1) this.highlighted++;
        },

        moveUp() {
            if (!this.open) return this.openDropdown();
            if (this.highlighted > 0) this.highlighted--;
        },

        selectHighlighted() {
            let opt = this.filteredOptions[this.highlighted];
            if (!opt) return;
            this.toggleOption(opt.value);
        },
        init() {
            $nextTick(() => {
                this.selectedValue = this.$refs.hiddenSelect.value;
                console.log(this.selectedValue);
            });
        }
    }"
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
            'required' => $required ? '' : null,
            'disabled' => $disabled ? '' : null,
            'aria-describedby' => $info ? "$id-help" : null,
            'class' => 'hidden',
        ],
        ...$atts,
    ]) !!}>
        @foreach ($options as $option)
            <option value="{{ data_get($option, 'value') }}">{!! data_get($option, 'label') !!}</option>
        @endforeach
    </select>
    {{-- Hidden real select (for form submit compatibility) --}}
    {{-- <select
        x-ref="realSelect"
        name="{{ $attributes->get('name', $id) }}{{ $multiple ? '[]' : '' }}"
        id="{{ $id }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        class="hidden"
        @if ($modelName) wire:model="{{ $modelName }}" @endif>
        @foreach ($options as $option)
            <option value="{{ data_get($option, 'value') }}">
                {!! data_get($option, 'label') !!}
            </option>
        @endforeach
    </select> --}}

    {{-- Main input view --}}
    <button
        type="button"
        x-on:click="open ? closeDropdown() : openDropdown()"
        x-on:keydown.arrow-down.prevent="moveDown()"
        x-on:keydown.arrow-up.prevent="moveUp()"
        x-on:keydown.enter.prevent="selectHighlighted()"
        x-on:keydown.escape.prevent="closeDropdown()"
        class="{{ css_classes([
            'form-select flex items-center justify-between cursor-pointer',
            $class => $class,
            'error' => $error,
            'has-start-icon' => !empty($startIcon) || !empty($startView),
            'has-end-icon' => !empty($endIcon) || !empty($endView),
        ]) }}"
        {{ $disabled ? 'disabled' : '' }}>
        <span class="truncate" x-text="selectedLabel"></span>

        <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

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
                class="form-control sm pill" />
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
                        'font-semibold': isSelected(opt.value)
                    }"
                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between">
                    <span x-html="opt.label"></span>

                    <template x-if="isSelected(opt.value)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
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
