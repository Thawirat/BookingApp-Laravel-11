import './bootstrap';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import '../css/app.css';
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)

Alpine.start()

import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

console.log("📡 Reverb connected", window.Echo);
