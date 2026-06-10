import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : 'soketi',
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    encrypted: (import.meta.env.VITE_PUSHER_SCHEME ?? 'http') === 'https',
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

// Listen for notifications
window.Echo.channel('notifications')
    .listen('NotificationSent', (data) => {
        console.log('New notification:', data);
        window.dispatchEvent(new CustomEvent('new-notification', { detail: data }));
    });

// Listen for notifications
window.Echo.channel('notifications')
    .listen('NotificationSent', (data) => {
        console.log('New notification:', data);
        window.dispatchEvent(new CustomEvent('new-notification', { detail: data }));
    });