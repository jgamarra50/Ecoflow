import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Minify assets
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
            },
        },
        // Chunk size warnings
        chunkSizeWarningLimit: 1000,
        // Rollup options
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor code
                    'vendor': ['alpinejs'],
                },
            },
        },
    },
});
