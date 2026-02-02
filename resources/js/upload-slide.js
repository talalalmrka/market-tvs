document.addEventListener("alpine:init", () => {
    Alpine.data("UploadSlide", ({ slotId }) => ({
        slotId: slotId,
        uploading: false,
        progress: 10,
        handleFileSelect(e) {
            // console.log(e.target.files, e.target.files.length);
            if (e.target.files.length) {
                this.$wire.fileSlot = slotId;
                const file = e.target.files[0];
                this.uploading = true;
                this.progress = 0;

                this.$wire.upload('file', file, (uploadedFilename) => {
                    // Success callback...
                    this.uploading = false;
                    this.progress = 100;
                    // console.log(uploadedFilename);
                }, () => {
                    // Error callback...
                    this.uploading = false;
                    this.progress = 0;
                }, (event) => {
                    // Progress callback...
                    // event.detail.progress contains a number between 1 and 100 as the upload progresses
                    this.progress = event.detail.progress;
                }, () => {
                    // Cancelled callback...
                    this.uploading = false;
                    this.progress = false;
                })
            }

        },
    }));
});
