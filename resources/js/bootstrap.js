import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// --- 1. AXIOS SETUP ---
window.axios = axios;

// THE FIX: We use '/' so the browser automatically uses the current domain (Render)
// instead of trying to connect to 127.0.0.1
window.axios.defaults.baseURL = '/'; 

window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF token setup
const csrfToken = document.head.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
} else {
    console.error('CSRF token not found');
}

// Axios request interceptor (Handles Authentication)
window.axios.interceptors.request.use(config => {
    const sanctumToken = localStorage.getItem('sanctum_token');
    
    if (sanctumToken) {
        config.headers['Authorization'] = `Bearer ${sanctumToken}`;
        config.headers['X-XSRF-TOKEN'] = sanctumToken;
    }

    config.headers['Content-Type'] = 'application/json';
    config.headers['Accept'] = 'application/json';

    return config;
});

// --- 2. PUSHER / ECHO SETUP ---
window.Pusher = Pusher;
window.Echo = null;

// Helper to init auth cookies
async function initializeAuth() {
    try {
        await axios.get('/sanctum/csrf-cookie');
        console.log('CSRF cookie set successfully');
    } catch (error) {
        console.error('Failed to set CSRF cookie:', error);
    }
}

// Helper to start Pusher
function initializePusher() {
    // Only initialize if keys are present
    if (!window.Echo && import.meta.env.VITE_PUSHER_APP_KEY) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        const sanctum = localStorage.getItem('sanctum_token');

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/api/broadcasting/auth', 
            auth: {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Authorization': `Bearer ${sanctum}`
                }
            }
        });
    }
    return window.Echo;
}

// --- 3. INITIALIZATION ---
// This wrapper prevents "Top-level await" errors during the build
(async () => {
    await initializeAuth();
    initializePusher();
})();

// Exports
export { initializePusher };
window.initializePusher = initializePusher;

console.log('Bootstrap.js loaded');