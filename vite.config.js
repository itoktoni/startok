import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        proxy: {
            '/api': {
                target: 'http://127.0.0.1:8000',
                changeOrigin: true,
                secure: false,
            },
        },
    },
    define: {
        'import.meta.env.VITE_REVERB_APP_KEY': JSON.stringify(process.env.VITE_REVERB_APP_KEY || process.env.REVERB_APP_KEY),
        'import.meta.env.VITE_REVERB_HOST': JSON.stringify(process.env.VITE_REVERB_HOST || 'localhost'),
        'import.meta.env.VITE_REVERB_PORT': JSON.stringify(process.env.VITE_REVERB_PORT || '8080'),
        'import.meta.env.VITE_REVERB_SCHEME': JSON.stringify(process.env.VITE_REVERB_SCHEME || 'http'),
    },
});
