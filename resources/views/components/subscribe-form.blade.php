@props([
    'theme' => 'light',
    'placeholder' => __('Subscripe to newsletter'),
    'icon' => 'bi-search',
    'icon_position' => 'start',
    'size' => null,
    'class' => null,
    'atts' => [],
])
@php
    $hasStartIcon = !empty($icon) && $icon_position === 'start';
    $hasEndIcon = !empty($icon) && $icon_position === 'end';
@endphp
<form class="relative" method="get" action="{{ url('subscribe') }}">
    <div
        class="form-control-container">
        @if ($hasStartIcon)
            <span class="start-icon">
                @icon($icon)
            </span>
        @endif
        <input
            {{ $attributes->merge([
                ...[
                    'type' => 'email',
                    'name' => 'email',
                    'placeholder' => $placeholder,
                    'class' => css_classes([
                        'form-control',
                        $size => $size,
                        'has-start-icon' => $hasStartIcon,
                        'has-end-icon' => $hasEndIcon,
                        $class => $class,
                    ]),
                ],
                ...$atts,
            ]) }} />
        @if ($hasEndIcon)
            <span class="start-icon">
                @icon($icon)
            </span>
        @endif
    </div>
</form>
