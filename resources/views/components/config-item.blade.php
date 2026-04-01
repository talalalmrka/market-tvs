@props(['item', 'class' => null, 'atts' => []])
<div
    {{ $attributes->merge([
        ...[
            'class' => css_classes(['grid grid-cols-1 md:grid-cols-2 gap-3 py-2 border-b', $class]),
        ],
        ...$atts,
    ]) }}>
    <div class="col">
        <fgx:label :for="$item->id" :label="$item->label" />
        <span class="text-muted">{{ $item->id }}</span>
        <span class="text-muted">{{ $item->type }}</span>
    </div>
    <div class="col">
        @if ($item->type === 'string' || $item->type === 'NULL')
            <fgx:input wire:model.live="{{ $item->id }}" id="{{ $item->id }}" />
        @elseif($item->type === 'integer')
            <fgx:input type="number" wire:model.live="{{ $item->id }}" id="{{ $item->id }}" />
        @elseif($item->type === 'boolean')
            <fgx:switch wire:model.live="{{ $item->id }}" id="{{ $item->id }}"
                :checked="boolval($item->value)" />
        @else
            <span class="text-muted">{{ $item->type }}</span>
        @endif
        <div class="text-sm text-muted">
            {{ __('Default:') }} {{ $item->value }}
        </div>
        @if ($item->id === 'form.timezone')
            <div class="text-sm text-muted">
                {{ __('Current date:') }} {{ date('d/m/Y h:i:s a') }}
            </div>
        @endif
    </div>
</div>
