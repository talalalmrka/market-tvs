document.addEventListener("alpine:init", () => {
    Alpine.data("TimeSlotCollapse", ({ slotId }) => ({
        slotId: slotId,
        open: false,
        get storageKey() {
            return `slot-storage-${this.slotId}`;
        },
        toggle() {
            this.open = !this.open;
            localStorage.setItem(this.storageKey, this.open);
        },

        init() {
            this.open = localStorage.getItem(this.storageKey) == 'true';
        },
    }));
});
