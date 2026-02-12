/**
 * 1️⃣ Import Echo FIRST (so window.Echo is ready)
 */
import './echo'

/**
 * 2️⃣ Import Alpine and expose globally
 */
import Alpine from 'alpinejs'
window.Alpine = Alpine

/**
 * 3️⃣ Import libraries / plugins that depend on Alpine
 */
import 'fadgram-ui'
import './alpine-back-top'
import './screen-slideshow'
import './chat'
import { NavbarTransparentTop } from './navbar-transparent-top'

/**
 * 4️⃣ DOM ready logic
 */
document.addEventListener('DOMContentLoaded', () => {
    NavbarTransparentTop.init()
})

/**
 * 5️⃣ Start Alpine LAST
 */
Alpine.start()
