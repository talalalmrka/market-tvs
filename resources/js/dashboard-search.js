document.addEventListener("alpine:init", () => {
    Alpine.data('DashboardSearch', (url) => ({
        url: url,
        mobileMenu: false,
        search: false,
        term: null,
        items: [],
        loading: false,
        open() {
            this.search = true;
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });

        },
        close() {
            this.search = false;
        },
        searchBar: {
            ['@click']() {
                this.open();
            },
        },
        dialogBackDrop: {
            ['@click']() {
                this.close();
            },
            ['x-show']() {
                return this.search;
            },
        },
        dialog: {
            ['x-show']() {
                return this.search;
            },
        },
        get apiUrl() {
            return `${this.url}?term=${this.term ?? ''}`;
        },
        load() {
            this.loading = true;
            fetch(this.apiUrl, { method: "GET" })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    this.items = data;
                })
                .catch(error => {
                    const em = `Failed to search: ${error}`;
                    Toast.error(em);
                    console.error("Failed to search:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        init() {
            this.$watch('term', (newVal, oldVal) => {
                if (newVal != oldVal) {
                    this.load();
                }
            });
        }
    }));
});
