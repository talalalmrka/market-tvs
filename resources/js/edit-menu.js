document.addEventListener("alpine:init", () => {

    // Time slot collapse
    Alpine.data("MenuItemCollapse", (itemId) => ({
        itemId: itemId,
        open: false,
        get storageKey() {
            return `menu-item-storage-${this.itemId}`;
        },
        toggle() {
            this.open = !this.open;
            localStorage.setItem(this.storageKey, this.open);
        },

        init() {
            this.open = localStorage.getItem(this.storageKey) == 'true';
        },
    }));

    Alpine.data("MenuItemsAccordion", () => ({
        active: [],
        isOpen(id) {
            return this.active.includes(id);
        },
        toggle(id) {
            if (this.isOpen(id)) {
                const index = this.active.findIndex(item => item === id);
                if (index >= 0) {
                    this.active.splice(index, 1);
                }
            } else {
                this.active.push(id);
            }
            this.saveLocal();
        },
        get allOpen() {
            return this.active.length;
        },
        get allIds() {
            let ids = [];
            const items = document.querySelectorAll('.menu-structure-accordion-item');
            items.forEach((item) => {
                const id = item.dataset.id;
                if (id) {
                    ids.push(parseInt(id));
                }
            });
            return ids;
        },
        toggleAll() {
            if (this.allOpen) {
                this.active = [];
            } else {
                this.active = this.allIds;
            }
            this.saveLocal();
        },
        saveLocal() {
            localStorage.setItem('MenuItemsAccordion', JSON.stringify(this.active));
        },
        init() {
            const json = localStorage.getItem('MenuItemsAccordion');
            if (json) {
                this.active = JSON.parse(json);
            }

        }
    }));


});
