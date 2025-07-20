import { defineConfig, loadEnv } from 'vite'; // Added loadEnv
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => { // Add mode parameter
    // Load environment variables based on mode (dev/prod)
    const env = loadEnv(mode, process.cwd(), ['VITE_']);

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        // Explicitly defining the environment variables for Vite
        define: {
            'process.env': {}, // For compatibility 
            'import.meta.env.VITE_PUSHER_APP_KEY': JSON.stringify(env.VITE_PUSHER_APP_KEY),
            'import.meta.env.VITE_PUSHER_APP_CLUSTER': JSON.stringify(env.VITE_PUSHER_APP_CLUSTER),
        },
    };
});
