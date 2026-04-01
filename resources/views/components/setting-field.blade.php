@props(['setting', 'class' => null, 'atts' => []])
@php
    $model = "form.{$setting->key}";
@endphp
<div
    {{ $attributes->merge([
        ...[
            'class' => css_classes(['grid grid-cols-1 md:grid-cols-2 gap-3 py-2 border-b', $class]),
        ],
        ...$atts,
    ]) }}>
    <div class="col">
        <fgx:label :for="$model" :label="$setting->label" />
        <div class="flex flex-wrap items-center gap-2">
            <span class="badge badge-teal pill">{{ $setting->key }}</span>
            <span class="badge badge-primary pill">{{ $setting->type }}</span>
        </div>
    </div>
    <div class="col">
        @if ($setting->type === 'string' || $setting->type === 'NULL')
            <fgx:input wire:model.live="{{ $model }}" id="{{ $model }}" />
        @elseif($setting->type === 'integer')
            <fgx:input type="number" wire:model.live="{{ $model }}" id="{{ $model }}" />
        @elseif($setting->type === 'boolean')
            <fgx:switch wire:model.live="{{ $model }}" id="{{ $model }}"
                :checked="boolval($setting->value)" />
        @else
            <span class="text-muted">{{ $setting->type }}</span>
        @endif
        <div class="text-xs text-muted mt-0.5">
            {{ __('Default:') }} {{ config($setting->key) }}
        </div>
        @if ($setting->key === 'app.timezone')
            <div class="text-sm text-muted">
                {{ __('Current date:') }} {{ date('d/m/Y h:i:s a') }}
            </div>
        @endif
    </div>
</div>
