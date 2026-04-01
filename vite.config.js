import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dashboard.js',
                "resources/js/preview.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            // interval: 500,
            usePolling: true,
            ignored: [
                '**/storage/framework/views/**',
                '**/vendor/**',
                '**/lang/**',
                '**/public/**',
            ],
        },
    },
});
