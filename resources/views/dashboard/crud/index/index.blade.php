<x-card>
    <div class="p-1.5">
        <fgx:input
            type="search"
            id="search"
            class="xs pill w-35"
            wire:model.live.debounce.300ms="search"
            startIcon="bi-search"
            :placeholder="__('Search')" />
    </div>

    <ul class="list-group mt-3">
        @foreach ($this->items as $item)
            <li class="list-group-item">
                <a wire:navigate title="{{ data_get($item, 'table') }}" href="{{ data_get($item, 'url') }}"
                    class="flex items-center gap-2 hover:link-underline">
                    <i class="icon {{ data_get($item, 'icon') }}"></i>
                    {{ data_get($item, 'label') }}
                </a>
            </li>
        @endforeach
    </ul>
</x-card>
