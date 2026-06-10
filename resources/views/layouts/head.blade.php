<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'WMS Portal' }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.PUSH_API_URL = @js(config('push.api_url', ''));
        window.PUSH_AUTH_TOKEN = @js(session('push_auth_token') ?? '');
    </script>
    <script src="/js/push-notification.js" defer></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', async () => {
                try {
                    const reg = await navigator.serviceWorker.register('/sw.js');
                    if (reg.active) {
                        if (window.PUSH_API_URL) {
                            reg.active.postMessage({ type: 'SET_API_URL', apiUrl: window.PUSH_API_URL });
                        }
                        if (window.PUSH_AUTH_TOKEN) {
                            reg.active.postMessage({ type: 'SET_AUTH_TOKEN', token: window.PUSH_AUTH_TOKEN });
                        }
                    }
                } catch (e) {
                    console.error('SW registration failed:', e);
                }
            });
        }
    </script>
    @stack('styles')
</head>