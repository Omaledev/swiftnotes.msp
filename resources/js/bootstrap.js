// import axios from 'axios';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// // Axios setup
// window.axios = axios;
// window.axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://127.0.0.1:8000';
// window.axios.defaults.withCredentials = true; // For cookie authentication
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// // Add this after your axios setup
// window.axios.interceptors.request.use(config => {
//     const token = localStorage.getItem('sanctum_token');
//     if (token) {
//         config.headers['Authorization'] = `Bearer ${token}`;
//         config.headers['X-Requested-With'] = 'XMLHttpRequest';
//     }
//     return config;
// });

// // CSRF token setup
// const token = document.head.querySelector('meta[name="csrf-token"]');
// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found');
// }

// // Sanctum token to Axios headers
// window.axios.defaults.headers.common['Authorization'] =
//     localStorage.getItem('sanctum_token')
//         ? `Bearer ${localStorage.getItem('sanctum_token')}`
//         : '';

// // Pusher/Echo setup
// window.Pusher = Pusher;

// export function initializePusher() {
//     if (!window.Echo) {
//         window.Echo = new Echo({
//             broadcaster: 'pusher',
//             key: import.meta.env.VITE_PUSHER_APP_KEY,
//             cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//             forceTLS: true,
//             encrypted: true,
//             authEndpoint: '/broadcasting/auth',
//             auth: {
//                 headers: {
//                     'X-CSRF-TOKEN': token ? token.content : '',
//                     // Authorization header for Pusher (redundant but kept for consistency)
//                     ...(localStorage.getItem('sanctum_token') ? {
//                         'Authorization': `Bearer ${localStorage.getItem('sanctum_token')}`
//                     } : {})
//                 },
//             },
//             enabledTransports: ['ws', 'wss'],
//             disabledTransports: ['sockjs', 'xhr_polling']
//         });

//         // Debug logs
//         if (import.meta.env.VITE_APP_DEBUG === 'true') {
//             window.Pusher.logToConsole = true;
//             console.log('Pusher initialized with key:', import.meta.env.VITE_PUSHER_APP_KEY);
//         }
//     }
//     return window.Echo;
// }

// window.initializePusher = initializePusher;


import axios from 'axios';
import Echo from 'laravel-echo';  // Make sure this import exists
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
        config.headers['Authorization'] = `Bearer ${sanctumToken}`;
    }
    return config;
});

// Pusher/Echo setup
window.Pusher = Pusher;
window.Echo = null;  // Initialize as null

export function initializePusher() {
    if (!window.Echo) {
        const authHeaders = {
            'X-CSRF-TOKEN': csrfToken?.content || ''
        };

        const sanctumToken = localStorage.getItem('sanctum_token');
        if (sanctumToken) {
            authHeaders['Authorization'] = `Bearer ${sanctumToken}`;
        }

        window.Echo = new Echo({  // Now Echo is properly defined
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: authHeaders
            },
            enabledTransports: ['ws', 'wss'],
            disabledTransports: ['sockjs', 'xhr_polling']
        });

        if (import.meta.env.VITE_APP_DEBUG === 'true') {
            window.Pusher.logToConsole = true;
        }
    }
    return window.Echo;
}

// Make initializePusher available globally
window.initializePusher = initializePusher;
