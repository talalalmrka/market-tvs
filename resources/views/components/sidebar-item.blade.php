@props(['item'])
@if ($item->type === 'collapse')
    @component('components.nav-link-collapse', [
        ...$item->toArray(),
        ...[
            'items' => $item->items->toArray(),
        ],
    ])
    @endcomponent
@else
    @component('components.nav-link', $item->toArray())
    @endcomponent
@endif
