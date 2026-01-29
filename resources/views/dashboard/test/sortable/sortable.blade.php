<div class="{{ css_classes(['card', $class]) }}">
    <div class="card-header">
        <h5 class="card-title">Sortable</h5>
    </div>
    <div class="card-body">
        <fgx:status key="sort" class="mb-4" />
        <div wire:sort="sortItem" wire:sort:group="slides" class="grid grid-cols-4 gap-4">
            @foreach ($slides as $slide)
                <div class="col border rounded-2xl relative" wire:sort:item="{{ $slide['id'] }}">
                    <img src="{{ $slide['url'] }}" class="w-full h-full object-cover object-center"
                        alt="{{ $slide['name'] }}">
                    <div class="absolute bottom-0 w-full bg-black/20 text-white text-sm p-1.5 text-center">
                        Id: {{ $slide['id'] }}, Order: {{ $slide['order'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
