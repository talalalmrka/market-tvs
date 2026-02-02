document.addEventListener("alpine:init", () => {
    Alpine.data("UploadSlide", () => ({
        uploading: true,
        progress: 10,
        listeners: [],
        button: {
            ["@click"]() {
                this.top();
            },
        },
        top() {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        },
        toggle() {
            if (document.documentElement.scrollTop > 100) {
                this.show = true;
            } else {
                this.show = false;
            }
        },
        init() {
            this.$nextTick(() => {
                this.listeners.push(
                    document.addEventListener("scroll", () => {
                        this.toggle();
                    }),
                );
            });
        },
    }));
});
