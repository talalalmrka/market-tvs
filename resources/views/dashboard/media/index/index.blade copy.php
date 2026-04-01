<x-card>
    <div class="md:flex md:items-center md:gap-2">
        <div class="relative flex gap-2 items-center md:items-start p-1.5">
            @if ($this->hasButtons)
                <div class="btn-group sm">
                    @foreach ($this->buttons as $button)
                        {!! $button->render() !!}
                    @endforeach
                    @if ($this->buttonsView)
                        {!! $this->buttonsView !!}
                    @endif
                </div>
            @endif
        </div>
        <div class="md:flex-1 flex flex-wrap items-center gap-2 md:justify-end-safe p-1.5">
            @if ($this->filtersView)
                {!! $this->filtersView !!}
            @endif
            <div class="inline-flex items-center gap-1">
                <fgx:select id="perPage" class="xs pill has-end-icon" wire:model.live.debounce.300ms="perPage"
                    startIcon="bi-list" :options="per_page_options()" />
            </div>
            <div class="inline-flex items-center gap-1">
                <fgx:input type="search" id="search" class="xs pill w-35" wire:model.live.debounce.300ms="search"
                    startIcon="bi-search" :placeholder="__('Search')" />
            </div>
        </div>
    </div>
    <div class="flex items-center gap-2 mt-3">
        <fgx:checkbox id="selectAll" wire:model.live="selectAll" :label="__('Select all')" />
    </div>
    <div class="mt-3 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 text-sm">
        @foreach ($this->items as $media)
            <div class="col relative">
                <input
                    type="checkbox"
                    value="{{ $media->id }}"
                    id="select-item-{{ $media->id }}"
                    wire:model.live="selected"
                    class="absolute top-1.5 start-1.5 z-2" />
                <div
                    wire:click="details({{ $media->id }})"
                    class="relative flex items-center justify-center w-full aspect-square rounded border bg-gray/5 border-gray-300 dark:border-gray-600 text-xs cursor-pointer">
                    @switch($media->type)
                        @case('image')
                            {{ $media }}
                        @break

                        @case('video')
                            <video class="max-w-full max-h-full" controls>
                                <source src="{{ $media->original_url }}" type="{{ $media->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        @break

                        @default
                            <div class="text-center p-2">
                                <i class="icon {{ preview($media)->icon }} w-8 h-8"></i>
                                <div class="text-center font-medium">{{ $media->name }}</div>
                                <div class="text-center font-light">
                                    {{ $media->type === 'other' ? $media->mime_type : $media->type }}</div>
                                <div class="text-center font-light">{{ $media->humanReadableSize }}</div>
                            </div>
                    @endswitch
                </div>

            </div>

    </div>
    <div class="flex items-center justify-between text-xs mt-3">
        <span>
            {{ __(':count items.', ['count' => $this->count]) }}
        </span>
        <span>
            {{ __(':count selected.', ['count' => sizeof($selected)]) }}
        </span>
    </div>
    <div class="p-2.5">
        {!! $this->links() !!}
    </div>
    @teleport('body')
        @if (!empty($currentMedia))
            <div wire:show="showModal" wire:transition class="modal fade show">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $currentMedia->name }}</h5>
                            <button type="button" class="btn-close" x-on:click="$wire.toggle('showModal')">
                                <i class="icon bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="modal-body flex-1 overflow-y-auto">
                            @pre100($currentMedia)
                        </div>
                    </div>
                </div><!-- Modal Dialog -->
            </div><!-- Modal -->
        @endif
    @endteleport
    @teleport('body')
        @if (!empty($currentMedia))
            <div class="modal-backdrop show" wire:show="showModal" x-on:click="$wire.toggle('showModal')"></div>
        @endif
    @endteleport


</x-card>
