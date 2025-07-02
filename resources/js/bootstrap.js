import axios from 'axios'
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'video-sync-app',
    wsHost: '4cc5-134-215-236-18.ngrok-free.app',
    wsPort: 443,
    wssPort: 443,
    forceTLS: true,
    encrypted: true,
    enabledTransports: ['ws', 'wss'],
});

document.dispatchEvent(new Event('echo:ready'));