@props([
    'title' => null,
    'icon' => null,
])

<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    @if (!empty($title))
        <div class="card-header">
            <h5
                class="{{ css_classes(['card-title', 'flex items-center gap-2' => !empty($icon)]) }}">
                @if (!empty($icon))
                    <i class="icon {{ $icon }}"></i>
                    <span>{{ $title }}</span>
                @else
                    {{ $title }}
                @endif
            </h5>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if (isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
