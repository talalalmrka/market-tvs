@props(['screen'])
<div
    x-data="ShowScreen(@js($screen))"
    x-bind="slideShowContainer" x-ref="container" class="w-full max-w-[400px] h-auto bg-black overflow-hidden relative">
    <div x-bind="controls" class="absolute top-1 end-1 z-full">
        <button type="button" x-bind="buttonFullScreen" class="btn-circle btn-circle-secondary btn-circle-sm">
            <i class="icon bi-fullscreen"></i>
        </button>
    </div>
    <template x-if="slide">
        <div class="w-full h-full">
            <!-- Image -->
            <img
                x-show="slide.type === 'image'"
                :src="slide.url"
                class="w-full h-full object-cover">

            <!-- Video -->
            <video
                x-show="slide.type === 'video'"
                x-ref="video"
                :src="slide.url"
                class="w-full h-full object-cover"
                muted
                playsinline></video>
        </div>
    </template>
</div>
