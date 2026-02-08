<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>
        eruda.init();
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine init');

            Alpine.data('FgFullScreen', () => ({
                fullScreen: false,

                buttonFullScreen: {
                    [':class']() {
                        return {
                            'btn-circle-blue': this.fullScreen,
                            'btn-circle-light': !this.fullScreen,
                        };
                    },
                    ['@click']() {
                        this.toggleFullScreen();
                    },
                },

                toggleFullScreen() {
                    this.fullScreen
                        ? this.disableFullScreen()
                        : this.enableFullScreen();
                },

                enableFullScreen() {
                    try {
                        const element = this.$refs.container;
                        console.log(element);
                        if (element.requestFullscreen) {
                            element.requestFullscreen();
                        } else if (element.mozRequestFullScreen) {
                            element.mozRequestFullScreen();
                        } else if (element.webkitRequestFullscreen) {
                            element.webkitRequestFullscreen();
                        } else if (element.msRequestFullscreen) {
                            element.msRequestFullscreen();
                        }
                        if (screen.orientation && screen.orientation.lock) {
                        	screen.orientation.lock("landscape").then(() => {
                        		console.log("Locked to landscape");
                        	}).catch((error) => {
                        		console.error(`Orientation lock failed: ${error}`);
                        	});
                        }
                        this.fullScreen = true;
                    } catch (e) {
                        console.error(e);
                    }
                },

                disableFullScreen() {
                    try {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }

                        this.fullScreen = false;
                    } catch (e) {
                        console.error(e);
                    }
                },

                init() {
                    console.log('FgFullScreen init');
                },
            }));
        });
    </script>

    @vite(['resources/css/app.css'])
</head>

<body>
    <div x-data="FgFullScreen">
        <div
            x-ref="container"
            class="screen-slideshow-container bg-gray-700 text-white relative"
        >
            <h1>This is the container</h1>

            <button
                type="button"
                x-bind="buttonFullScreen"
                class="btn-circle absolute top-2 end-2"
            >
                <i class="icon bi-fullscreen"></i>
            </button>
        </div>
    </div>
</body>
</html>