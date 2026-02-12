<x-layouts::app.curve :title="$title">
    @if ($screens->isNotEmpty())
        @foreach ($screens as $screen)
            <div class="flex items-center gap-2 shadow border rounded-xl overflow-hidden mb-6">
                <x-screen-slideshow :screen="$screen" class="w-50" />
                <div
                    class="flex-1 flex items-center gap-2 p-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h5 class="flex-1">
                                <a title="{{ $screen->name }}" href="{{ $screen->permalink }}">
                                    {{ $screen->name }}
                                </a>
                            </h5>
                            <div class="text-muted flex items-center gap-2 text-xs">
                                @can('update', $screen)
                                    <a title="{{ __('Edit') }}" href="{{ $screen->edit_url }}" target="_blank"
                                        class="flex items-center gap-0.5">
                                        <i class="icon bi-pencil-square"></i>
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                @endcan
                                <span class="flex items-center gap-0.5">
                                    <i class="icon bi-clock"></i>
                                    <span>{{ $screen->date_ago }}</span>
                                </span>
                            </div>
                        </div>


                        <div class="flex flex-wrap gap-2">
                            @foreach ($screen->timeSlots as $slot)
                                <span class="badge badge-primary pill">
                                    {{ $slot->name }} {{ $slot->start_time_formatted }} -
                                    {{ $slot->end_time_formatted }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <a title="{{ $screen->name }}" target="_blank" href="{{ $screen->permalink }}">
                        <i class="icon bi-chevron-right rtl:rotate-180"></i>
                    </a>
                </div>
            </div>
        @endforeach
        {{ $screens->links() }}
    @else
        <fgx:alert soft :content="__('No items found!')" />
    @endif
</x-layouts::app.curve>
