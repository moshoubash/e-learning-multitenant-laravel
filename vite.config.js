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
        // Bind to all interfaces so the dev server is reachable both
        // from 'localhost' and from a tenant subdomain on the same
        // machine. 'origin' then forces the URLs Vite emits in
        // @vite/client, the CSS <link>, and the HMR WebSocket to
        // use 'http://localhost:5173' so the browser CSP source list
        // can match them.
        host: true,
        port: 5173,
        strictPort: false,
        origin: 'http://localhost:5173',
        cors: true,
        allowedHosts: true,
        hmr: {
            host: 'localhost',
            clientPort: 5173,
            protocol: 'ws',
        },
    },
});
