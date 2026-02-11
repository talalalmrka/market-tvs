import NoSleep from "nosleep.js";
document.addEventListener("alpine:init", () => {
    Alpine.data("ScreenSlideshow", (screenData) => ({
        container: null,
        debug: true,
        screenData: screenData,

        slotIndex: 0,
        slideIndex: 0,

        timer: null,
        slotTimer: null,
        slotDuration: screenData.slot_duration || 60000,
        controlsDuration: screenData.controls_duration || 60000,
        // slotInterval: screen.slotInterval || 60000,
        currentVideo: null,
        isPaused: true,
        showControls: false,
        // controlsInterval: 3000,
        controlsTimer: null,
        fullScreen: false,
        wakeLook: false,
        noSleep: null,
        refreshing: false,
        fullScreenListener: null,
        ["@mousemove"](evt) {
            this.startControls();
            console.log("Mouse move on:", evt.target);
            // this.debugMessage('Mouse moved on:');
            // this.debugMessage(evt);
        },
        /*slideShowContainer: {
            ["@mousemove"](evt) {
                this.startControls();
                // this.debugMessage('Mouse moved on:');
                // this.debugMessage(evt);
            }
        },*/
        slideContainer(_slide) {
            return {
                ["@mousemove"](evt) {
                    this.startControls();
                    // this.debugMessage('Mouse moved on slide:');
                    // this.debugMessage(evt);
                }
            };
        },
        topBar: {
            ["x-show"]() {
                return this.showControls;
            },
            ["@mouseenter"]() {
                this.clearControlsTimer();
                this.showControls = true;
                // this.debugMessage('Mouse entered Top bar');
            },
            ["@mouseleave"]() {
                this.startControls();
                // this.debugMessage('Mouse leaved Top bar');
            }
        },
        bottomBar: {
            ["x-show"]() {
                return this.showControls;
            },
            ["@mouseenter"]() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ["@mouseleave"]() {
                this.startControls();
            }
        },
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
        buttonNoSleep: {
            [":class"]() {
                return {
                    "btn-circle-blue": this.wakeLook,
                    "btn-circle-light": !this.wakeLook
                };
            },
            ["@click"]() {
                this.toggleNoSleep();
            }
        },
        buttonRefresh: {
            ["x-html"]() {
                return this.refreshing
                    ? '<i class="icon fg-loader-dots-bounce"></i>'
                    : '<i class="icon bi-arrow-repeat"></i>';
            },
            ["@click"]() {
                this.refresh();
            }
        },
        buttonPrev: {
            ["x-show"]() {
                return this.showControls;
            },
            ["@click"]() {
                this.prevSlide();
            },
            ["@mouseenter"]() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ["@mouseleave"]() {
                this.startControls();
            }
        },
        buttonNext: {
            ["x-show"]() {
                return this.showControls;
            },
            ["@click"]() {
                this.nextSlide();
            },
            ["@mouseenter"]() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ["@mouseleave"]() {
                this.startControls();
            }
        },
        buttonPlayPause: {
            ["x-show"]() {
                return this.showControls;
            },
            ["@click"]() {
                this.playPause();
            },
            ["@mouseenter"]() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ["@mouseleave"]() {
                this.startControls();
            }
        },

        buttonSlot(slot) {
            return {
                ["x-text"]() {
                    return slot.name;
                },
                [":class"]() {
                    const index = this.getSlotIndex(slot);
                    const isCurrent = this.slotIndex == index;
                    return {
                        "bg-blue hover:bg-blue": isCurrent,
                        "bg-secondary/50 hover:bg-secondary": !isCurrent
                    };
                },
                ["@click"]() {
                    const index = this.getSlotIndex(slot);
                    this.clearTimer();
                    this.slotIndex = index;
                    this.slideIndex = 0;
                    this.playSlide();
                }
            };
        },

        init() {
            this.container = this.$el;
            this.initNoSleep();
            this.addFullScreenListener();
            this.normalize();
            this.start();
            this.startSlot();
        },
        initNoSleep() {
            if (!this.noSleep) {
                this.noSleep = new NoSleep();
            }
        },
        addFullScreenListener() {
            if (!this.fullScreenListener) {
                this.fullScreenListener = document.addEventListener('fullscreenchange', () => {
                    this.fullScreen = !!document.fullscreenElement;
                    console.log(document.fullscreenElement);
                });
            }
        },
        // =========================
        // Helpers
        // =========================

        normalize() {
            // keep only active slots & slides
            this.screenData.time_slots = this.screenData.time_slots
                .filter(slot => slot.is_active)
                .sort((a, b) => (a.priority ?? 0) - (b.priority ?? 0))
                .map(slot => ({
                    ...slot,
                    slides: slot.slides
                        .filter(slide => slide.is_active)
                        .sort((a, b) => a.order - b.order)
                }));
        },
        get nowMinutes() {
            const now = new Date();
            return now.getHours() * 60 + now.getMinutes();
        },

        timeToMinutes(time) {
            // "HH:mm" or "HH:mm:ss"
            const [h, m] = time.split(":");
            return parseInt(h) * 60 + parseInt(m);
        },
        getSlotIndex(_slot) {
            const index = this.slots.findIndex(s => s.id == _slot.id);
            return index;
        },
        getCurrentSlotIndex() {
            const now = this.nowMinutes;

            const index = this.screenData.time_slots.findIndex(slot => {
                if (!slot.start_time || !slot.end_time) {
                    return false;
                }

                const start = this.timeToMinutes(slot.start_time);
                const end = this.timeToMinutes(slot.end_time);

                // normal range (08:00 → 14:00)
                if (start <= end) {
                    return now >= start && now < end;
                }

                // overnight range (22:00 → 05:00)
                return now >= start || now < end;
            });

            // fallback to first slot
            return index !== -1 ? index : 0;
        },
        getSlideIndex(_slide) {
            const index = this.slides.findIndex(s => s.id == _slide.id);
            return index;
        },
        get slots() {
            return Array.isArray(this.screenData.time_slots)
                ? this.screenData.time_slots
                : [];
        },
        get slot() {
            return this.slots[this.slotIndex] ?? null;
        },

        get slides() {
            return Array.isArray(this.slot?.slides) ? this.slot?.slides : [];
        },
        get slide() {
            return this.slides[this.slideIndex] ?? null;
        },

        // =========================
        // Playback
        // =========================

        startt() {
            if (!this.slot || !this.slide) {
                return;
            }
            this.playSlide();
        },
        startSlot() {
            this.clearSlotTimer();
            this.slotTimer = setInterval(() => {
                const newIndex = this.getCurrentSlotIndex();

                if (newIndex !== this.slotIndex) {
                    this.clearTimer();
                    this.slotIndex = newIndex;
                    this.slideIndex = 0;
                    this.playSlide();
                }
            }, this.slotDuration);
        },
        start() {
            if (!this.screenData.time_slots.length) {
                return;
            }

            this.slotIndex = this.getCurrentSlotIndex();
            this.slideIndex = 0;

            if (this.debug) {
                // console.log("Current slot:", this.slot);
            }

            this.playSlide();
        },
        playPause() {
            // Toggles playback between playing and paused.
            if (this.isPaused) {
                this.isPaused = false;
                this.playSlide();
            } else {
                this.isPaused = true;
                this.clearTimer();
                if (
                    this.currentVideo &&
                    typeof this.currentVideo.pause === "function"
                ) {
                    this.currentVideo.pause();
                }
            }
        },
        playSlide() {
            this.clearTimer();

            if (!this.slide) {
                return;
            }

            if (this.slide.type === "video") {
                this.playVideo();
            } else {
                this.playImage();
            }
            this.isPaused = false;
        },

        playImage() {
            const duration =
                this.slide.duration ?? this.slot.slide_duration ?? 5000;

            this.timer = setTimeout(() => {
                this.nextSlide();
            }, duration);
        },

        playVideo() {
            this.$nextTick(() => {
                const video = this.$refs.video;
                if (!video) return;

                this.currentVideo = video;
                video.currentTime = 0;
                video.play();

                video.onended = () => {
                    this.nextSlide();
                };
            });
        },

        // =========================
        // Navigation
        // =========================
        prevSlide() {
            this.clearTimer();

            if (this.slideIndex > 0) {
                this.slideIndex--;
            } else {
                this.slideIndex = this.slot.slides.length - 1;
            }

            this.playSlide();
        },
        nextSlide() {
            this.clearTimer();

            if (this.slideIndex < this.slot.slides.length - 1) {
                this.slideIndex++;
            } else {
                this.slideIndex = 0;
                // this.nextSlot()
            }

            this.playSlide();
        },

        nextSlot() {
            if (this.slotIndex < this.screenData.time_slots.length - 1) {
                this.slotIndex++;
            } else {
                this.slotIndex = 0;
            }
        },

        // =========================
        // Utils
        // =========================

        clearTimer() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }

            if (this.currentVideo) {
                this.currentVideo.pause();
                this.currentVideo = null;
            }
        },
        clearSlotTimer() {
            if (this.slotTimer) {
                clearInterval(this.slotTimer);
                this.slotTimer = null;
            }
        },
        startControls() {
            this.clearControlsTimer();
            this.showControls = true;
            this.controlsTimer = setTimeout(() => {
                this.showControls = false;
            }, this.controlsDuration);
        },
        clearControlsTimer() {
            if (this.controlsTimer) {
                clearTimeout(this.controlsTimer);
                this.controlsTimer = null;
            }
        },
        toggleFullScreen() {
            console.log('Toggle full screen');
            if (this.fullScreen) {
                this.disableFullScreen();
            } else {
                this.enableFullScreen();
            }
        },
        enableFullScreen() {
            try {
                // console.log(element);
                if (this.container.requestFullscreen) {
                    this.container.requestFullscreen();
                } else if (this.container.mozRequestFullScreen) {
                    this.container.mozRequestFullScreen();
                } else if (this.container.webkitRequestFullscreen) {
                    this.container.webkitRequestFullscreen();
                } else if (this.container.msRequestFullscreen) {
                    this.container.msRequestFullscreen();
                }
                this.enableLandscape();
                this.enableNoSleep();
                this.fullScreen = true;
            } catch (e) {
                console.error(e);
                Toast.error(e.toString());
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
                this.disableLandscape();
                this.disableNoSleep();
                this.fullScreen = false;
            } catch (e) {
                console.error(e);
            }
        },

        toggleNoSleep() {
            if (this.wakeLook) {
                this.disableNoSleep();
            } else {
                this.enableNoSleep();
            }
        },
        enableNoSleep() {
            try {
                this.noSleep.enable();
                this.wakeLook = true;
            } catch (error) {
                console.error(error);
            }
        },
        disableNoSleep() {
            try {
                this.noSleep.disable();
                this.wakeLook = false;
            } catch (error) {
                console.error(error);
            }
        },
        enableLandscape() {
            try {
                if (window.screen.orientation && window.screen.orientation.lock) {
                    window.screen.orientation
                        .lock("landscape")
                        .then(() => {
                            console.log("Locked to landscape");
                        })
                        .catch(error => {
                            console.log(`Orientation lock failed: ${error}`);
                        });
                }
            } catch (error) {
                console.error(error);
            }
        },
        disableLandscape() {
            try {
                if (window.screen.orientation && window.screen.orientation.unlock) {
                    window.screen.orientation.unlock();
                    console.log("Orientation unlocked");
                }
            } catch (error) {
                console.error(error);
            }
        },
        updateData(_data) {
            this.clearAllTimers();
            this.screenData = _data;
            this.normalize();
            this.start();
            this.startSlot();
            Toast.success('Screen refresh done');
        },
        refresh() {
            this.refreshing = true;
            fetch(this.screenData.api_url, { method: "GET" })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {

                    const newScreenData = data.data ? data.data : null;
                    console.log(newScreenData);
                    if (newScreenData) {
                        this.updateData(newScreenData);
                    } else {
                        Toast.error('Screen refresh failed');
                    }
                    // Update screenData with the latest data from the API
                    // this.screenData = data.data ? data.data : data;
                    // Optionally, reset slide/slot index if needed:
                    // this.slotIndex = 0;
                    // this.slideIndex = 0;
                })
                .catch(error => {
                    Toast.error(`Failed to refresh screen data: ${error}`);
                    console.error("Failed to refresh screen data:", error);
                })
                .finally(() => {
                    this.refreshing = false;
                });
        },
        clearAllTimers() {
            this.clearTimer();
            this.clearControlsTimer();
            this.clearSlotTimer();
        },
        debugMessage(message) {
            if (this.debug) {
                console.log(message);
            }
        },

        destroy() {
            this.clearAllTimers();
        }

    }));
});
