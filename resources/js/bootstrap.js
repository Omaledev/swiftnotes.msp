import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// --- 1. AXIOS SETUP ---
window.axios = axios;

// THE FIX: Set baseURL to '/' so it automatically uses your Render URL
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

// Axios request interceptor
window.axios.interceptors.request.use(config => {
    const sanctumToken = localStorage.getItem('sanctum_token');
    
    if (sanctumToken) {
        config.headers['Authorization'] = `Bearer ${sanctumToken}`;
        // Note: X-XSRF-TOKEN is usually handled by cookies, but this is fine to keep
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
    if (!window.Echo) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        const sanctum = localStorage.getItem('sanctum_token');

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/api/broadcasting/auth', // Make sure this matches your api.php routes
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
// We wrap this in an async IIFE to handle the top-level await cleanly
(async () => {
    await initializeAuth();
    initializePusher();
})();

// Exports
export { initializePusher };
window.initializePusher = initializePusher;

console.log('Bootstrap.js loaded');