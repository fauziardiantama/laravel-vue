import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
if (localStorage.getItem('token')) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
}
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     //wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     //: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     //enabledTransports: ['ws', 'wss'],
// });
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "f832e8f2e80a36ce8f8d",
    cluster: 'ap1',
    wsHost: `ws-ap1.pusher.com`,
    authEndpoint: 'api/broadcasting/auth',
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
    },
    //wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    //: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: true,
    //enabledTransports: ['ws', 'wss'],
});