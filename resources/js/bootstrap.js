import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

import Echo from 'laravel-echo'
window.Pusher = require('pusher-js')

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,           // “local”
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,   // “mt1”
  wsHost: import.meta.env.VITE_PUSHER_HOST,           // your ngrok host
  wsPort: import.meta.env.VITE_PUSHER_PORT,           // 6001
  wssPort: import.meta.env.VITE_PUSHER_PORT,
  forceTLS: true,
  encrypted: true,
  enabledTransports: ['ws', 'wss'],
})
