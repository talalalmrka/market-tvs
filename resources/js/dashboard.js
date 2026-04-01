import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import './helpers';
import './alpine-back-top';
import './edit-screen';
import './screen-slideshow';
import './fg-accordion';
import './edit-menu';
import './my-sort';
import './sort-group';
import './dashboard-search';
// import './sort';
import { NavbarTransparentTop } from './navbar-transparent-top';
import { initFadgramUI } from "fadgram-ui";
// import accordion from 'fadgram-ui/js/accordion';
// Alpine.plugin(accordion);
document.addEventListener("livewire:navigated", () => {
    initFadgramUI();
    NavbarTransparentTop.init();
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
Livewire.start()

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// import './echo';
