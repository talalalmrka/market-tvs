@props([
    'id' => 'selected',
    'item',
])
<fgx:checkbox id="{{ $id }}.{{ $item->getKey() }}" wire:model.live="{{ $id }}"
    value="{{ $item->getKey() }}" />
