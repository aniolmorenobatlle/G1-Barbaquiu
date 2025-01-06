// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher', // canvia 'reverb' per 'pusher'
//     key: import.meta.env.VITE_PUSHER_APP_KEY, // Hauria de ser PUSHER_APP_KEY i no REVERB_APP_KEY
//     wsHost: import.meta.env.VITE_PUSHER_HOST,  // També canvia les variables d'entorn de REVERB a PUSHER
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });