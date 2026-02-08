document.addEventListener("alpine:init", () => {

    // Time slot collapse
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

    // Edit screen
    Alpine.data("EditScreen", () => ({
        slideId: null,
        toggleSlideModal(id) {
            this.slideId = (this.slideId === id) ? null : id;
        },
        closeModal() {
            this.slideId = null;
        },
        slideModalToggler(id) {
            return {
                ['@click']() {
                    this.toggleSlideModal(id);
                },
            };
        },
        slideModalShown(id) {
            return this.slideId === id;
        },
        slideModal(id) {
            return {
                [':class']() {
                    return {
                        'modal': true,
                        'fade': true,
                        'show': this.slideId === id,
                    };
                },
            };
        },
        backDrop: {
            ['@click']() {
                this.closeModal();
            },
            [':class']() {
                return {
                    'modal-backdrop': true,
                    'show': this.slideId !== null
                };
            },
        },
    }));

    // Upload slide
    Alpine.data("UploadSlide", ({ slotId }) => ({
        slotId: slotId,
        uploading: false,
        progress: 10,
        fileInput: {
            ['@change'](e) {
                const file = e.target.files[0];
                if (file) {
                    this.upload(file);
                }
            },
        },
        progressContainer: {
            ['x-show']() {
                return this.uploading;
            },
        },
        buttonCancelUpload: {
            ['@click']() {
                this.$wire.cancelUpload('file');
            },
        },
        uploadState({ uploading = null, progress = null }) {
            if (uploading != null) {
                this.uploading = uploading;
            }
            if (progress != null) {
                this.progress = progress;
            }
        },
        upload(file) {
            this.$wire.fileSlot = slotId;
            this.uploadState({ uploading: true, progress: 0 });
            this.$wire.upload('file', file, (uploadedFilename) => {
                // Success callback...
                this.uploadState({ uploading: false, progress: 100 });
            }, () => {
                // Error callback...
                this.uploadState({ uploading: false });
            }, (event) => {
                // Progress callback...
                // event.detail.progress contains a number between 1 and 100 as the upload progresses
                this.uploadState({ progress: event.detail.progress });
            }, () => {
                // Cancelled callback...
                this.uploadState({ uploading: false, progress: 0 });
            })
        },
    }));
});
