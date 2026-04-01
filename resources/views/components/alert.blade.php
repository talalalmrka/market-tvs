@props([
    'class' => null,
    'atts' => [],
    'type' => 'info',
    'soft' => false,
    'outline' => false,
    'size' => null,
    'sm' => false,
    'xs' => false,
    'icon' => null,
    'iconClass' => null,
    'content' => null,
    'hideIcon' => false,
    'info' => false,
    'success' => false,
    'warning' => false,
    'error' => false,
])
@php
    $types = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'error' => 'alert-error',
        'warning' => 'alert-warning',
        'primary' => 'alert-primary',
        'secondary' => 'alert-secondary',
        'accent' => 'alert-accent',
        'neutral' => 'alert-neutral',
        'base' => 'alert-base',
        'light' => 'alert-light',
        'dark' => 'alert-dark',
        'blue' => 'alert-blue',
        'indigo' => 'alert-indigo',
        'purple' => 'alert-purple',
        'pink' => 'alert-pink',
        'red' => 'alert-red',
        'orange' => 'alert-orange',
        'yellow' => 'alert-yellow',
        'green' => 'alert-green',
        'teal' => 'alert-teal',
        'cyan' => 'alert-cyan',
        'gray' => 'alert-gray',
        'slate' => 'alert-slate',
        'zinc' => 'alert-zinc',
        'stone' => 'alert-stone',
        'amber' => 'alert-amber',
        'lime' => 'alert-lime',
        'emerald' => 'alert-emerald',
        'sky' => 'alert-sky',
        'violet' => 'alert-violet',
        'fuchsia' => 'alert-fuchsia',
        'rose' => 'alert-rose',
    ];

    $outlineTypes = [
        'info' => 'alert-outline-info',
        'success' => 'alert-outline-success',
        'error' => 'alert-outline-error',
        'warning' => 'alert-outline-warning',
        'primary' => 'alert-outline-primary',
        'secondary' => 'alert-outline-secondary',
        'accent' => 'alert-outline-accent',
        'neutral' => 'alert-outline-neutral',
        'base' => 'alert-outline-base',
        'light' => 'alert-outline-light',
        'dark' => 'alert-outline-dark',
        'blue' => 'alert-outline-blue',
        'indigo' => 'alert-outline-indigo',
        'purple' => 'alert-outline-purple',
        'pink' => 'alert-outline-pink',
        'red' => 'alert-outline-red',
        'orange' => 'alert-outline-orange',
        'yellow' => 'alert-outline-yellow',
        'green' => 'alert-outline-green',
        'teal' => 'alert-outline-teal',
        'cyan' => 'alert-outline-cyan',
        'gray' => 'alert-outline-gray',
        'slate' => 'alert-outline-slate',
        'zinc' => 'alert-outline-zinc',
        'stone' => 'alert-outline-stone',
        'amber' => 'alert-outline-amber',
        'lime' => 'alert-outline-lime',
        'emerald' => 'alert-outline-emerald',
        'sky' => 'alert-outline-sky',
        'violet' => 'alert-outline-violet',
        'fuchsia' => 'alert-outline-fuchsia',
        'rose' => 'alert-outline-rose',
    ];

    $softTypes = [
        'info' => 'alert-soft-info',
        'success' => 'alert-soft-success',
        'error' => 'alert-soft-error',
        'warning' => 'alert-soft-warning',
        'primary' => 'alert-soft-primary',
        'secondary' => 'alert-soft-secondary',
        'accent' => 'alert-soft-accent',
        'neutral' => 'alert-soft-neutral',
        'base' => 'alert-soft-base',
        'light' => 'alert-soft-light',
        'dark' => 'alert-soft-dark',
        'blue' => 'alert-soft-blue',
        'indigo' => 'alert-soft-indigo',
        'purple' => 'alert-soft-purple',
        'pink' => 'alert-soft-pink',
        'red' => 'alert-soft-red',
        'orange' => 'alert-soft-orange',
        'yellow' => 'alert-soft-yellow',
        'green' => 'alert-soft-green',
        'teal' => 'alert-soft-teal',
        'cyan' => 'alert-soft-cyan',
        'gray' => 'alert-soft-gray',
        'slate' => 'alert-soft-slate',
        'zinc' => 'alert-soft-zinc',
        'stone' => 'alert-soft-stone',
        'amber' => 'alert-soft-amber',
        'lime' => 'alert-soft-lime',
        'emerald' => 'alert-soft-emerald',
        'sky' => 'alert-soft-sky',
        'violet' => 'alert-soft-violet',
        'fuchsia' => 'alert-soft-fuchsia',
        'rose' => 'alert-soft-rose',
    ];

    $sizes = [
        'xxs' => 'alert-xxs',
        'xs' => 'alert-xs',
        'sm' => 'alert-sm',
        'lg' => 'alert-lg',
        'xl' => 'alert-xl',
        'xxl' => 'alert-xxl',
    ];

    $icons = [
        'info' => 'bi-info-circle',
        'success' => 'bi-check2-circle',
        'warning' => 'bi-exclamation-triangle',
        'error' => 'bi-x-circle',
    ];
    foreach ($types as $t => $tclass) {
        $tfound = $attributes->has($t) || (bool) data_get(get_defined_vars(), $t);
        if ($tfound) {
            $type = $t;
        }
    }

    foreach ($sizes as $s => $sclass) {
        $sfound = $attributes->has($s) || (bool) data_get(get_defined_vars(), $s);
        if ($sfound) {
            $size = $s;
        }
    }

    $soft = $soft || $attributes->has('soft');
    $outline = $outline || $attributes->has('outline');
    $typeClass = data_get($types, $type);
    $sizeClass = $size ? data_get($sizes, $size) : null;

    $iconClassName = !$hideIcon ? $icon ?? data_get($icons, $type) : null;
    $hasIcon = !empty($iconClassName);
    $content = isset($slot) && $slot->isNotEmpty() ? $slot : $content;

@endphp
<div
    {{ $attributes->merge(
        array_merge(
            [
                'class' => css_classes([
                    'alert',
                    $typeClass => $typeClass,
                    $size => $size,
                    'flex-space-2' => $hasIcon,
                    'alert-soft' => $soft,
                    'alert-outline' => $outline,
                    $class => $class,
                ]),
            ],
            $atts,
        ),
    ) }}>
    @if ($iconClassName)
        <i class="icon {{ $iconClassName }} {{ $iconClass }}"></i>
    @endif
    @if ($content)
        <div class="grow">
            {!! $content !!}
        </div>
    @endif

</div>
