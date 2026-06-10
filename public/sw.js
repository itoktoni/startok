let API_BASE_URL = '';
let AUTH_TOKEN = '';

self.addEventListener('message', function (event) {
    if (event.data) {
        if (event.data.type === 'SET_API_URL') {
            API_BASE_URL = event.data.apiUrl || '';
        }
        if (event.data.type === 'SET_AUTH_TOKEN') {
            AUTH_TOKEN = event.data.token || '';
        }
    }
});

self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body || '',
        icon: data.icon || '/apple-touch-icon.png',
        badge: data.badge || '/favicon.ico',
        image: data.image || undefined,
        vibrate: data.vibrate || [200, 100, 200],
        data: {
            url: data.url || '/',
            ...data.data,
        },
        actions: data.actions || [],
        tag: data.tag || undefined,
        renotify: data.renotify || false,
        requireInteraction: data.requireInteraction || false,
        silent: data.silent || false,
        timestamp: data.timestamp || Date.now(),
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});

self.addEventListener('pushsubscriptionchange', function (event) {
    event.waitUntil(
        self.registration.pushManager.subscribe(event.oldSubscription.options).then(function (subscription) {
            var headers = { 'Content-Type': 'application/json' };
            if (AUTH_TOKEN) {
                headers['Authorization'] = 'Bearer ' + AUTH_TOKEN;
            }
            return fetch(API_BASE_URL + '/api/push/subscribe', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(subscription.toJSON()),
            });
        })
    );
});
