import './time-slot-collapse';
import './alpine-back-top';
import './upload-slide';
import './fg-toast';
import { initFadgramUI } from "fadgram-ui";
// import FgToast from "./fg-toast";
document.addEventListener("livewire:navigated", () => {
    initFadgramUI();
    // NavbarTransparentTop.init();

});
let toastListener = null;
document.addEventListener("livewire:init", () => {
    if (!toastListener) {
        toastListener = Livewire.on("toast", (event) => {
            const data = event[0];
            if (!FgToast) {
                console.log("Window toast not inited!");
            }
            FgToast.make(data.message, data.options);
        });
    }
});

