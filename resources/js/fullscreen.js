document.addEventListener("alpine:init", () => {
    Alpine.data("FgFullScreen", screen => ({
        fullScreen: false,
        buttonFullScreen: {
            [":class"]() {
                return {
                    "btn-circle-blue": this.fullScreen,
                    "btn-circle-light": !this.fullScreen
                };
            },
            ["@click"]() {
                this.toggleFullScreen();
            }
        },
        toggleFullScreen() {
            if (this.fullScreen) {
                this.disableFullScreen();
            } else {
                this.enableFullScreen();
            }
        },
        enableFullScreen() {
            try {
                const element = this.$refs.container;
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen();
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
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
        }
    }));
});
