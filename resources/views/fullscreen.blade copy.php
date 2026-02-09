<!DOCTYPE html>
<html {!! locale_attributes() !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ get_the_title('FullScreen') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body>

    <div class="container px-2 lg:px-4 p-4 pb-20">
        <div
            x-data="FullScreen"
            x-bind="slideShowContainer" x-ref="container"
            class="relative w-full aspect-video overflow-hidden bg-gray-500 text-white">
            <div class="w-full h-full flex flex-col items-center justify-center-safe">
                <h1>SlideShow</h1>
            </div>

            <div x-bind="topBar" class="absolute top-0 w-full text-xs font-semibold">
                <div class="flex items-center gap-2 justify-between p-2">
                    <div class="flex items-center gap-2">


                    </div>
                    <div class="flex items-center gap-2">

                        <button type="button" x-bind="buttonFullScreen"
                            class="btn-circle btn-circle-sm" title="Toggle fullscreen"
                            aria-label="Toggle fullscreen">
                            <i class="icon bi-fullscreen"></i>
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @if (config('app.eruda_enabled'))
        <script type="text/javascript" src="{{ url('assets/eruda/eruda.js') }}"></script>
        <script>
            eruda.init();
        </script>
    @endif
    @vite(['resources/js/fullscreen.js'])
    @stack('scripts')
</body>

</html>
