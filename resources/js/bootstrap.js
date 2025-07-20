import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Axios setup
window.axios = axios;
window.axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://127.0.0.1:8000';
window.axios.defaults.withCredentials = true; // For cookie authentication
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF token setup
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

// Sanctum token to Axios headers 
window.axios.defaults.headers.common['Authorization'] = 
    localStorage.getItem('sanctum_token') 
        ? `Bearer ${localStorage.getItem('sanctum_token')}` 
        : '';

// Pusher/Echo setup
window.Pusher = Pusher;

export function initializePusher() {
    if (!window.Echo) {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': token ? token.content : '',
                    // Authorization header for Pusher (redundant but kept for consistency)
                    ...(localStorage.getItem('sanctum_token') ? {
                        'Authorization': `Bearer ${localStorage.getItem('sanctum_token')}`
                    } : {})
                },
            },
            enabledTransports: ['ws', 'wss'],
            disabledTransports: ['sockjs', 'xhr_polling']
        });

        // Debug logs
        if (import.meta.env.VITE_APP_DEBUG === 'true') {
            window.Pusher.logToConsole = true;
            console.log('Pusher initialized with key:', import.meta.env.VITE_PUSHER_APP_KEY);
        }
    }
    return window.Echo;
}

window.initializePusher = initializePusher;