document.addEventListener("alpine:init", () => {
    Alpine.data("BackTopButton", () => ({
        show: false,
        listeners: [],
        button: {
            ["@click"]() {
                this.top();
            },
            [":class"]() {
                return {
                    'transition-all': true,
                    'opacity-0 translate-y-80': !this.show,
                };
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
