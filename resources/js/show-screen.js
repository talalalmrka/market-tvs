import NoSleep from 'nosleep.js';
document.addEventListener('alpine:init', () => {
    Alpine.data('ShowScreen', (screen) => ({
        debug: true,
        screen: screen,

        slotIndex: 0,
        slideIndex: 0,

        timer: null,
        slotTimer: null,
        slotInterval: screen.slotInterval || 60000,
        currentVideo: null,
        isPaused: true,
        showControls: false,
        controlsInterval: 3000,
        controlsTimer: null,
        fullScreen: false,
        wakeLook: false,
        containerElement: null,
        noSleep: null,
        slideShowContainer: {
            ['@mousemove'](evt) {
                /* this.startControls();
                this.debugMessage('Mouse moved on:');
                this.debugMessage(evt); */
            },
        },
        slideContainer(_slide) {
            return {
                ['@mousemove'](evt) {
                    this.startControls();
                    // this.debugMessage('Mouse moved on slide:');
                    // this.debugMessage(evt);
                }
            };
        },
        topBar: {
            ['x-show']() {
                return this.showControls;
            },
            ['@mouseenter']() {
                this.clearControlsTimer();
                this.showControls = true;
                // this.debugMessage('Mouse entered Top bar');
            },
            ['@mouseleave']() {
                this.startControls();
                // this.debugMessage('Mouse leaved Top bar');
            },
        },
        bottomBar: {
            ['x-show']() {
                return this.showControls;
            },
            ['@mouseenter']() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ['@mouseleave']() {
                this.startControls();
            },
        },
        buttonFullScreen: {
            [':class']() {
                return {
                    'btn-circle-blue': this.fullScreen,
                    'btn-circle-light': !this.fullScreen,
                };
            },
            ['@click']() {
                this.toggleFullScreen();
            },
        },
        buttonNoSleep: {
            [':class']() {
                return {
                    'btn-circle-blue': this.wakeLook,
                    'btn-circle-light': !this.wakeLook,
                };
            },
            ['@click']() {
                this.toggleNoSleep();
            },
        },
        buttonPrev: {
            ['x-show']() {
                return this.showControls;
            },
            ['@click']() {
                this.prevSlide()
            },
            ['@mouseenter']() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ['@mouseleave']() {
                this.startControls();
            },
        },
        buttonNext: {
            ['x-show']() {
                return this.showControls;
            },
            ['@click']() {
                this.nextSlide()
            },
            ['@mouseenter']() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ['@mouseleave']() {
                this.startControls();
            },
        },
        buttonPlayPause: {
            ['x-show']() {
                return this.showControls;
            },
            ['@click']() {
                this.playPause();
            },
            ['@mouseenter']() {
                this.clearControlsTimer();
                this.showControls = true;
            },
            ['@mouseleave']() {
                this.startControls();
            },
        },

        buttonSlot(slot) {
            return {
                ['x-text']() {
                    return slot.name;
                },
                [':class']() {
                    const index = this.getSlotIndex(slot);
                    const isCurrent = this.slotIndex == index;
                    return {
                        'bg-blue hover:bg-blue': isCurrent,
                        'bg-secondary/50 hover:bg-secondary': !isCurrent,
                    };
                },
                ['@click']() {
                    const index = this.getSlotIndex(slot);
                    this.clearTimer();      // وقف أي Timer شغال
                    this.slotIndex = index; // حدد الـ slot المختار
                    this.slideIndex = 0;        // ابدأ من أول slide
                    this.playSlide();           // شغّل الـ slide

                    if (this.debug) {
                        console.log('Slot manually changed:', slot.id, 'Index:', index);
                    }
                },
            };
        },

        init() {
            this.noSleep = new NoSleep();
            this.normalize()
            this.start()
            this.startSlot()
        },

        // =========================
        // Helpers
        // =========================

        normalize() {
            // keep only active slots & slides
            this.screen.time_slots = this.screen.time_slots
                .filter(slot => slot.is_active)
                .sort((a, b) => (a.priority ?? 0) - (b.priority ?? 0))
                .map(slot => ({
                    ...slot,
                    slides: slot.slides
                        .filter(slide => slide.is_active)
                        .sort((a, b) => a.order - b.order)
                }))
        },
        get nowMinutes() {
            const now = new Date()
            return now.getHours() * 60 + now.getMinutes()
        },

        timeToMinutes(time) {
            // "HH:mm" or "HH:mm:ss"
            const [h, m] = time.split(':')
            return (parseInt(h) * 60) + parseInt(m)
        },
        getSlotIndex(_slot) {
            const index = this.slots.findIndex(s => s.id == _slot.id);
            return index;
        },
        getCurrentSlotIndex() {
            const now = this.nowMinutes

            const index = this.screen.time_slots.findIndex(slot => {
                if (!slot.start_time || !slot.end_time) return false

                const start = this.timeToMinutes(slot.start_time)
                const end = this.timeToMinutes(slot.end_time)

                // normal range (08:00 → 14:00)
                if (start <= end) {
                    return now >= start && now < end
                }

                // overnight range (22:00 → 05:00)
                return now >= start || now < end
            })

            // fallback to first slot
            return index !== -1 ? index : 0
        },
        getSlideIndex(_slide) {
            const index = this.slides.findIndex(s => s.id == _slide.id);
            return index;
        },
        get slots() {
            return Array.isArray(this.screen.time_slots)
                ? this.screen.time_slots
                : [];
        },
        get slot() {
            return this.slots[this.slotIndex] ?? null
        },

        get slides() {
            return Array.isArray(this.slot?.slides)
                ? this.slot?.slides
                : [];
        },
        get slide() {
            return this.slides[this.slideIndex] ?? null
        },

        // =========================
        // Playback
        // =========================

        startt() {
            if (!this.slot || !this.slide) return
            this.playSlide()
        },
        startSlot() {
            this.clearSlotTimer();
            this.slotTimer = setInterval(() => {
                const newIndex = this.getCurrentSlotIndex()

                if (newIndex !== this.slotIndex) {
                    this.clearTimer()
                    this.slotIndex = newIndex
                    this.slideIndex = 0
                    this.playSlide()

                    if (this.debug) {
                        console.log('Slot changed:', this.slot)
                    }
                }
            }, 60000)
        },
        start() {
            if (!this.screen.time_slots.length) return

            this.slotIndex = this.getCurrentSlotIndex()
            this.slideIndex = 0

            if (this.debug) {
                console.log('Current slot:', this.slot)
            }

            this.playSlide()
        },
        playPause() {
            // Toggles playback between playing and paused.
            if (this.isPaused) {
                this.isPaused = false;
                this.playSlide();
            } else {
                this.isPaused = true;
                this.clearTimer();
                if (this.currentVideo && typeof this.currentVideo.pause === 'function') {
                    this.currentVideo.pause();
                }
            }
        },
        playSlide() {
            this.clearTimer()

            if (!this.slide) return

            if (this.slide.type === 'video') {
                this.playVideo()
            } else {
                this.playImage()
            }
            this.isPaused = false;
        },

        playImage() {
            const duration =
                this.slide.duration ??
                this.slot.slide_duration ??
                5000

            this.timer = setTimeout(() => {
                this.nextSlide()
            }, duration)
        },

        playVideo() {
            this.$nextTick(() => {
                const video = this.$refs.video
                if (!video) return

                this.currentVideo = video
                video.currentTime = 0
                video.play()

                video.onended = () => {
                    this.nextSlide()
                }
            })
        },

        // =========================
        // Navigation
        // =========================
        prevSlide() {
            this.clearTimer()

            if (this.slideIndex > 0) {
                this.slideIndex--
            } else {
                this.slideIndex = this.slot.slides.length - 1
            }

            this.playSlide()
        },
        nextSlide() {
            this.clearTimer()

            if (this.slideIndex < this.slot.slides.length - 1) {
                this.slideIndex++
            } else {
                this.slideIndex = 0
                // this.nextSlot()
            }

            this.playSlide()
        },

        nextSlot() {
            if (this.slotIndex < this.screen.time_slots.length - 1) {
                this.slotIndex++
            } else {
                this.slotIndex = 0
            }
        },

        // =========================
        // Utils
        // =========================

        clearTimer() {
            if (this.timer) {
                clearTimeout(this.timer)
                this.timer = null
            }

            if (this.currentVideo) {
                this.currentVideo.pause()
                this.currentVideo = null
            }
        },
        clearSlotTimer() {
            if (this.slotTimer) {
                clearInterval(this.slotTimer)
                this.slotTimer = null
            }
        },
        startControls() {
            this.clearControlsTimer();
            this.showControls = true;
            this.controlsTimer = setTimeout(() => {
                this.showControls = false;
            }, this.controlsInterval);
        },
        clearControlsTimer() {
            if (this.controlsTimer) {
                clearTimeout(this.controlsTimer)
                this.controlsTimer = null
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
                this.fullScreen = false;
            } catch (e) {
                console.error(e);
                Toast.error(e.toString());
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

        debugMessage(message) {
            if (this.debug) {
                console.log(message);
            }
        }
    }));
});
