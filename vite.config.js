import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { createHtmlPlugin } from 'vite-plugin-html';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        createHtmlPlugin({
            minify: true,
            entry: '/resources/js/app.js', // Menentukan entry point utama aplikasi Anda
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                format: 'es' // Output menggunakan ES Modules
            }
        }
    }
});
