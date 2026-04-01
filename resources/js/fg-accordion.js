document.addEventListener("alpine:init", () => {
    Alpine.data('FgAccordion', () => ({
        activeId: null,
        isActive(id) {
            return id == this.activeId;
        },
        toggle(id) {
            if (this.activeId == id) {
                this.activeId = null;
            } else {
                this.activeId = id;
            }
            localStorage.setItem('menuAccordion', this.activeId);
        },
        item(id) {
            return {
                [":class"]() {
                    return {
                        'accordion-item': true,
                        'active': this.isActive(id),
                    }
                },
            };
        },
        itemHead(id) {
            return {
                ['@click']() {
                    this.toggle(id);
                },
                [":class"]() {
                    return {
                        'accordion-header select-none gap-2': true,
                    }
                },
            };
        },
        itemBody(id) {
            return {
                ['x-show']() {
                    return this.activeId == id;
                },
                [":class"]() {
                    return {
                        'accordion-body': true,
                    }
                },
            };
        },
        init() {
            this.activeId = localStorage.getItem('menuAccordion') || null;
        },
    }));
});
