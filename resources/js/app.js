import './time-slot-collapse';
import './alpine-back-top';
import { initFadgramUI } from "fadgram-ui";
import Toast from "./toast";
window.Toast = Toast;
document.addEventListener("livewire:navigated", () => {
    initFadgramUI();
    // NavbarTransparentTop.init();

});
document.addEventListener('DOMContentLoaded', () => {
    // initFadgramUI();
});
let toastListener = null;
document.addEventListener("livewire:init", () => {
    if (!toastListener) {
        toastListener = Livewire.on("toast", (event) => {
            const data = event[0];
            if (!Toast) {
                console.log("Window toast not inited!");
            }
            Toast.make(data.message, data.options);
        });
    }
});
// console.log('Toast', Toast);

