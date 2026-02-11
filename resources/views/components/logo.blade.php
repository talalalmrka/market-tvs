@props([
    'id' => 'logo',
    'theme' => 'dark',
    'context' => 'navbar',
    'navigate' => false,
    'label' => get_option('name'),
    'labelEnabled' => (bool) get_option('logo_label_enabled'),
    'width' => get_option('logo_width'),
    'height' => get_option('logo_height'),
    'class' => null,
    'atts' => [],
    'style' => null,
    'labelClass' => null,
])

@php
    $styles = [];
    if (!empty($width)) {
        $styles[] = "width: {$width}px";
    }
    if (!empty($height)) {
        $styles[] = "height: {$height}px";
    }
    $collection = $theme === 'light' ? 'logo_light' : 'logo';
    $setting = setting($collection);
    $hasMedia = instance_setting($setting) && $setting->hasMedia($collection);
    $labelEnabled = ($labelEnabled && !empty($label)) || !$hasMedia;
    $src = $setting?->getFirstMediaUrl($collection, $context);
    $srcset = $setting?->getSrcset($context);
@endphp
<a {{ $navigate ? 'wire:navigate' : '' }} {!! $attributes->merge([
    ...[
        'id' => $id,
        'href' => route('home'),
        'title' => $label,
        'class' => css_classes(['navbar-logo', 'flex items-center gap-2' => $labelEnabled, $class => $class]),
        'style' => css_styles([...[$style], ...$styles]),
    ],
    ...$atts,
]) !!}>
    @if ($hasMedia)
        <img
            src="{{ $src }}"
            @if ($srcset) srcset="{{ $srcset }}" @endif
            alt="{{ $label }} Logo"
            class="h-auto"
            style="{{ css_styles($styles) }}">
    @endif
    @if ($labelEnabled)
        <span
            class="{{ css_classes(['navbar-logo-label', $labelClass => $labelClass]) }}">{{ $label }}</span>
    @endif
</a>
