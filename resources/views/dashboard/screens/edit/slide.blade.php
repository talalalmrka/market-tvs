<div wire:key="slide-{{ $slide['id'] }}"
    {{-- data-fg-toggle="modal"
	data-fg-target="#edit-slide-{{ $slide['id'] }}" --}}
    class="col aspect-video relative border hover:shadow rounded-lg overflow-hidden cursor-pointer bg-gray-700"
    wire:sort:item="{{ $slide['id'] }}">
    <div wire:click="editSlide({{ $slide['id'] }})"
        wire:sort:ignore class="relative">
        <img src="{{ $slide['url'] }}"
            class="w-full h-full object-cover"
            alt="{{ $slide['id'] }}">
        <fgx:loader wire:loading wire:target="editSlide({{ $slide['id'] }})"
            class="absolute top-1/2 -translate-y-1/2 start-1/2 -translate-x-1/2" />
    </div>

    <div
        class="absolute bottom-0 w-full text-xs font-semibold text-white text-shadow text-shadow-black flex items-center gap-2 justify-between z-2 p-1">
        <span>#{{ $slide['id'] }}</span>
        <span>⇵{{ $slide['order'] }}</span>
    </div>
    <div
        class="absolute top-0 w-full text-shadow text-shadow-black text-sm text-white font-semibold p-1 flex gap-2 items-center justify-between z-2">
        <button wire:sort:handle type="button"
            class="cursor-move text-xl text-shadow text-shadow-black"
            title="{{ __('Move') }}">
            <i class="icon bi-list"></i>
        </button>
        <button
            wire:click="removeSlide({{ $slide['id'] }})"
            wire:sort:ignore type="button"
            class="btn-circle btn-circle-sm btn-circle-red"
            title="{{ __('Delete') }}">
            <i wire:loading.remove
                wire:target="removeSlide({{ $slide['id'] }})"
                class="icon bi-trash-fill"></i>
            <fgx:loader wire:loading
                wire:target="removeSlide({{ $slide['id'] }})" />
        </button>
    </div>
</div>
