<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-body">
        <div wire:sort="sortItem" wire:sort:group="slides" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($slides as $slide)
                <div class="col p-3 border rounded-2xl relative"
                    wire:sort:item="{{ $slide['id'] }}">
                    <button wire:sort:handle type="button"
                        class="cursor-move text-xl text-shadow text-shadow-black"
                        title="{{ __('Move') }}">
                        <i class="icon bi-list"></i>
                    </button>
                    <div class="flex items-center justify-between">
                        <span>Id: {{ $slide['id'] }}</span>
                        <span>Order: {{ $slide['order'] }}</span>
                    </div>
                    <div class="text-xl">
                        {{ $slide['name'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
