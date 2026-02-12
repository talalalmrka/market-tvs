@props(['screen', 'class' => null, 'atts' => [], 'slideClass' => null, 'slideAtts' => []])
@php
    if (method_exists($screen, 'toArray')) {
        $screen = $screen->toArray();
    }
@endphp
<div
    x-cloak
    x-data="ScreenSlideshow(@js($screen))"
    {!! $attributes->merge([
        ...[
            'class' => css_classes(['relative aspect-video overflow-hidden', $class]),
        ],
        ...$atts,
    ]) !!}>
    <template x-if="slide">
        <div
            x-bind="slideContainer(slide)"
            @atts(['class' => css_classes(['relative w-full h-full', $slideClass])], $slideAtts)>
            <img
                x-show="slide.type === 'image'"
                :src="slide.url"
                class="w-full h-full object-cover object-center">
            <video
                x-show="slide.type === 'video'"
                x-ref="video"
                :src="slide.url"
                class="w-full h-full object-cover object-center"
                muted
                playsinline
                controls></video>
        </div>
    </template>

    <div x-bind="topBar" class="absolute top-0 w-full text-xs font-semibold">
        <div class="flex items-center gap-2 justify-between p-2">
            <div class="flex items-center gap-2">
                <span class="bg-blue text-white pill px-2 py-1" x-text="`#${slide.id}`"></span>
                <span class="bg-green text-white pill px-2 py-1"
                    x-text="`⥮ ${slideIndex + 1}/${slides.length}`"></span>
                <span class="bg-teal text-white pill px-2 py-1" x-text="`➲ ${slide.transition}`"></span>

            </div>
            <div class="flex items-center gap-2">
                <button type="button" x-bind="buttonRefresh"
                    class="btn-circle btn-circle-sm btn-circle-light" title="Refresh"
                    aria-label="Toggle no sleep">
                    <i class="icon"></i>
                </button>
                <button type="button" x-bind="buttonNoSleep"
                    class="btn-circle btn-circle-sm" title="Toggle no sleep"
                    aria-label="Toggle no sleep">
                    <i class="icon bi-lightbulb"></i>
                </button>
                <button type="button" x-bind="buttonFullScreen"
                    class="btn-circle btn-circle-sm" title="Toggle fullscreen"
                    aria-label="Toggle fullscreen">
                    <i class="icon bi-fullscreen"></i>
                </button>
            </div>
        </div>
    </div>
    <button type="button" x-bind="buttonPrev"
        class="btn-circle btn-circle-sm btn-circle-light absolute top-1/2 -translate-y-1/2 start-2" title="Previous"
        aria-label="Previous">
        <i class="icon bi-chevron-left"></i>
    </button>
    <button type="button" x-bind="buttonPlayPause"
        class="btn-circle btn-circle-sm btn-circle-light absolute top-1/2 -translate-y-1/2 start-1/2 -translate-x-1/2"
        title="Play pause"
        aria-label="Play pause">
        <i class="icon" :class="{ 'bi-play-fill': isPaused, 'bi-pause-fill': !isPaused }"></i>
    </button>
    <button type="button" x-bind="buttonNext"
        class="btn-circle btn-circle-sm btn-circle-light absolute top-1/2 -translate-y-1/2 end-2" title="Previous"
        aria-label="Previous">
        <i class="icon bi-chevron-right"></i>
    </button>
    <div x-bind="bottomBar"
        class="absolute bottom-0 w-full text-xs font-semibold">
        <div class="flex w-full items-center justify-center-safe gap-2">
            <template x-for="_slot in slots" :key="_slot.id">
                <button type="button" x-bind="buttonSlot(_slot)"
                    class="px-2 py-1 rounded-t-xl text-white" :title="_slot.name" :aria-label="_slot.name">
                </button>
            </template>
        </div>
    </div>
</div>
