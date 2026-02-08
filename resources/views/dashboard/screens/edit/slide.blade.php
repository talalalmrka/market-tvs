<div wire:key="slide-{{ $slide['id'] }}"
    class="col relative border hover:shadow rounded-lg overflow-hidden cursor-pointer bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 hover:ring-2 hover:ring-primary/50"
    wire:sort:item="{{ $slide['id'] }}">
    <div
        x-bind="slideModalToggler({{ $slide['id'] }})"
        wire:sort:ignore class="relative w-full h-full">
        @if ($slide['type'] == 'image')
            <img src="{{ $slide['url'] }}"
                class="w-full h-full object-cover object-center"
                alt="{{ $slide['id'] }}">
        @elseif ($slide['type'] == 'video')
            <video
                class="w-full h-full object-cover object-center"
                muted
                autoplay
                controls>
                <source src="{{ $slide['url'] }}" />
            </video>
        @endif

    </div>
    <div
        class="absolute bottom-0 w-full text-xs font-semibold text-white text-shadow text-shadow-black flex items-center gap-2 justify-between z-2 p-1">
        <span class="badge badge-blue pill"># {{ $slide['id'] }}</span>
        <span class="badge badge-green pill">⇵ {{ $slide['order'] }}</span>
    </div>
    <div
        class="absolute top-0 w-full text-shadow text-shadow-black text-sm text-white font-semibold p-1 flex gap-2 items-center justify-between z-2">
        <button wire:sort:handle type="button"
            class="cursor-move text-2xl text-shadow text-shadow-black"
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
