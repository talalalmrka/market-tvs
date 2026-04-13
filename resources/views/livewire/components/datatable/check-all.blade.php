@props([
    'id' => 'selectAll',
])
<fgx:checkbox id="{{ $id }}" wire:model.live="{{ $id }}" />
