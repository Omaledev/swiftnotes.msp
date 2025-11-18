import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Axios setup
window.axios = axios;
window.axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://127.0.0.1:8000';
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
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${sanctumToken}`;
            console.log('Sanctum token set:', sanctumToken);
        } else {
            console.warn('No Sanctum token found in localStorage');
        }


    if (sanctumToken) {
        config.headers['Authorization'] = `Bearer ${sanctumToken}`;
        config.headers['X-XSRF-TOKEN'] = sanctumToken;
    }

    // Content type set
    config.headers['Content-Type'] = 'application/json';
    config.headers['Accept'] = 'application/json';

    return config;
});

// Pusher/Echo setup
window.Pusher = Pusher;
window.Echo = null;


// Add this before initializing Pusher
async function initializeAuth() {
    try {
        await axios.get('/sanctum/csrf-cookie');
        console.log('CSRF cookie set successfully');
    } catch (error) {
        console.error('Failed to set CSRF cookie:', error);
    }
}

// Call it before Pusher initialization
await initializeAuth();
initializePusher();

function initializePusher() {
    if (!window.Echo) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const sanctumToken = localStorage.getItem('sanctum_token');

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/api/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': `Bearer ${sanctumToken}`
                }
            },
            // Add these options for better connection handling
            wsHost: import.meta.env.VITE_PUSHER_HOST || `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
            wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
            wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
            disableStats: true,
            enabledTransports: ['ws', 'wss']
        });
    }
    return window.Echo;
}

// Export for ES modules
export { initializePusher, }
// Make initializePusher available globally
window.initializePusher = initializePusher;



console.log('Bootstrap.js loaded');
