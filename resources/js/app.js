// import './time-slot-collapse';
import './alpine-back-top';
// import './upload-slide';
import './edit-screen';
import './screen-slideshow';
import { initFadgramUI } from "fadgram-ui";
document.addEventListener("livewire:navigated", () => {
    initFadgramUI();
    // NavbarTransparentTop.init();

});
let toastListener = null;
document.addEventListener("livewire:init", () => {
    if (!toastListener) {
        toastListener = Livewire.on("toast", (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            if (!toast) {
                console.log("Window toast not inited!");
            }
            toast(data.message, data.options);
        });
    }
});


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
