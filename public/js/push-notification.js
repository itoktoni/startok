const PushNotification = {
    swRegistration: null,
    isSubscribed: false,
    apiBaseUrl: (window.PUSH_API_URL || '').replace(/\/$/, ''),
    authToken: window.PUSH_AUTH_TOKEN || null,

    _getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
        if (this.authToken) {
            headers['Authorization'] = 'Bearer ' + this.authToken;
        } else {
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) {
                headers['X-CSRF-TOKEN'] = meta.content;
            }
        }
        return headers;
    },

    async init() {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            console.warn('Push notifications are not supported');
            return false;
        }

        try {
            this.swRegistration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered');

            if (this.swRegistration.active && this.apiBaseUrl) {
                this.swRegistration.active.postMessage({
                    type: 'SET_API_URL',
                    apiUrl: this.apiBaseUrl,
                });
            }

            if (this.swRegistration.active && this.authToken) {
                this.swRegistration.active.postMessage({
                    type: 'SET_AUTH_TOKEN',
                    token: this.authToken,
                });
            }

            const subscription = await this.swRegistration.pushManager.getSubscription();
            this.isSubscribed = subscription !== null;

            return true;
        } catch (error) {
            console.error('Service Worker registration failed:', error);
            return false;
        }
    },

    async getVapidKey() {
        try {
            const response = await fetch(this.apiBaseUrl + '/api/push/vapid-key', {
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();
            return data.publicKey;
        } catch (error) {
            console.error('Failed to get VAPID key:', error);
            return null;
        }
    },

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    },

    async subscribe() {
        if (!this.swRegistration) {
            await this.init();
        }

        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                throw new Error('Notification permission denied');
            }

            const vapidKey = await this.getVapidKey();
            if (!vapidKey) {
                throw new Error('VAPID key not available');
            }

            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(vapidKey),
            });

            const response = await fetch(this.apiBaseUrl + '/api/push/subscribe', {
                method: 'POST',
                headers: this._getHeaders(),
                body: JSON.stringify(subscription.toJSON()),
            });

            if (!response.ok) {
                throw new Error('Failed to save subscription');
            }

            this.isSubscribed = true;
            return true;
        } catch (error) {
            console.error('Failed to subscribe:', error);
            throw error;
        }
    },

    async unsubscribe() {
        try {
            const subscription = await this.swRegistration.pushManager.getSubscription();
            if (!subscription) {
                this.isSubscribed = false;
                return true;
            }

            await subscription.unsubscribe();

            await fetch(this.apiBaseUrl + '/api/push/unsubscribe', {
                method: 'POST',
                headers: this._getHeaders(),
                body: JSON.stringify({ endpoint: subscription.endpoint }),
            });

            this.isSubscribed = false;
            return true;
        } catch (error) {
            console.error('Failed to unsubscribe:', error);
            throw error;
        }
    },

    async checkStatus() {
        try {
            const response = await fetch(this.apiBaseUrl + '/api/push/status', {
                headers: {
                    'Accept': 'application/json',
                    ...(this.authToken ? { 'Authorization': 'Bearer ' + this.authToken } : {}),
                },
            });
            const data = await response.json();
            this.isSubscribed = data.subscribed;
            return data.subscribed;
        } catch (error) {
            console.error('Failed to check status:', error);
            return false;
        }
    },

    getPermissionState() {
        if (!('Notification' in window)) return 'unsupported';
        return Notification.permission;
    },
};

window.PushNotification = PushNotification;
