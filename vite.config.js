import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // Force 'localhost' instead of '[::]' (Vite's IPv6 default).
        // The browser CSP source list cannot express IPv6 in brackets,
        // and the @vite/client script tag must use a hostname the
        // browser can match against a CSP host-source.
        host: 'localhost',
        port: 5173,
        strictPort: false,
        cors: true,
        allowedHosts: true,
        hmr: {
            host: 'localhost',
            clientPort: 5173,
            protocol: 'ws',
        },
    },
});
