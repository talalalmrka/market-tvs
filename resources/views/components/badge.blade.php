@props([
    'label' => null,
    'icon' => null,
    'color' => null,
    'outline' => false,
    'pill' => false,
    'size' => null,
])
@php
    $colors = [
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
        'light' => 'badge-light',
        'dark' => 'badge-dark',
        'red' => 'badge-red',
        'orange' => 'badge-orange',
        'amber' => 'badge-amber',
        'yellow' => 'badge-yellow',
        'lime' => 'badge-lime',
        'green' => 'badge-green',
        'emerald' => 'badge-emerald',
        'teal' => 'badge-teal',
        'cyan' => 'badge-cyan',
        'sky' => 'badge-sky',
        'blue' => 'badge-blue',
        'indigo' => 'badge-indigo',
        'violet' => 'badge-violet',
        'purple' => 'badge-purple',
        'fuchsia' => 'badge-fuchsia',
        'pink' => 'badge-pink',
        'rose' => 'badge-rose',
        'slate' => 'badge-slate',
        'gray' => 'badge-gray',
        'zinc' => 'badge-zinc',
        'neutral' => 'badge-neutral',
        'stone' => 'badge-stone',
    ];
    $badgeColor = data_get($colors, $color, 'primary');

    $sizes = [
        'xs' => 'xs',
        'sm' => 'sm',
        'lg' => 'lg',
        'xl' => 'xl',
        'xxl' => 'xxl',
    ];
    $hasLabel = !empty($label);
    $hasIcon = !empty($icon);
    $hasContent = $hasLabel || $hasIcon;
    $flex = $hasLabel && $hasIcon;
@endphp
@if ($hasContent)
    <span
        class="{{ css_classes(['badge', $badgeColor, 'badge-outline' => $outline, $size => $size, 'pill' => $pill, 'flex items-center gap-0.5' => $flex]) }}">
        @if ($hasIcon)
            <i class="icon {{ $icon }} me-1.5"></i>
        @endif
        @if ($hasLabel)
            {!! $label !!}
        @endif
    </span>
@endif
