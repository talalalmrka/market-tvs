@props([
    'label' => null,
    'icon' => null,
    'open' => false,
    'items' => [],
])
<div x-cloak x-data="{ open: @js($open) }" class="block w-full">
    <a href="#!" x-on:click.prevent="open = !open"
        class="flex-space-2 text-sm w-full px-3 py-2 justify-between">
        @icon($icon)
        <span class="grow text-start">{{ $label }}</span>
        <i x-cloak class="icon bi-chevron-down" :class="{ 'rotate-180': open }"></i>
    </a>
    <nav x-collapse x-show="open" class="nav vertical space-y-3 ms-3 px-2 py-3 border-s border-s-white/50">
        @if (!empty($items))
            @foreach ($items as $item)
                @if (data_get($item, 'type') === 'collapse')
                    @component('components.nav-link-collapse', $item)
                    @endcomponent
                @else
                    @component('components.nav-link', $item)
                    @endcomponent
                @endif
            @endforeach
        @else
            @if (isset($slot))
                {{ $slot }}
            @endif
        @endif
    </nav>
</div>
