import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing.css',
                'resources/css/dashboard.css',
                'resources/css/admin.css',
                'resources/css/reservas.css',
                'resources/css/pagos.css',
                'resources/css/multimedia.css',
                'resources/js/app.js',
                'resources/js/landing.js',
            ],
            refresh: true,
        }),
    ],
});