<div class="lg:hidden fixed top-0 left-0 right-0 h-11 bg-surface-container-lowest border-b border-outline-variant shadow-sm py-7 flex items-center px-3 z-50">
    <button class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-surface-container transition-colors" onclick="toggleSB()">
        <span class="material-symbols-outlined text-on-surface-variant">menu</span>
    </button>
    <span class="ml-2 font-headline-md text-headline-md text-on-surface">{{ $title ?? config('app.name') }}</span>
    <div class="flex-1"></div>

    @auth
    <div class="relative">
        <button onclick="document.getElementById('pushDropdown').classList.toggle('hidden')" class="btn btn-xs py-4 px-2 rounded-3xl relative">
            <span class="icon-[tabler--bell] size-4"></span>
            <span id="pushDot" class="hidden absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></span>
        </button>

        <div id="pushDropdown" class="hidden absolute right-0 top-full mt-2 w-72 bg-base-100 border border-base-300 rounded-xl shadow-lg z-50 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold">Notifikasi Push</span>
                <span class="icon-[tabler--x] size-4 cursor-pointer text-base-content/40" onclick="document.getElementById('pushDropdown').classList.add('hidden')"></span>
            </div>

            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                <div>
                    <p id="pushStatus" class="text-xs font-medium">Memeriksa...</p>
                    <p id="pushDesc" class="text-[10px] text-base-content/50"></p>
                </div>
                <button id="pushToggle" onclick="pushToggle()" class="relative w-11 h-6 rounded-full transition-colors bg-gray-300">
                    <span id="pushKnob" class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
                </button>
            </div>
            <p id="pushError" class="hidden text-[10px] text-error mt-2"></p>
            <p id="pushDebug" class="hidden text-[10px] text-green-600 mt-1"></p>
            <a href="{{ route('notifications') }}" class="block mt-3 text-xs text-center text-primary hover:underline">Pengaturan lengkap</a>
        </div>
    </div>
    @endauth
</div>

<script>
(function() {
    var _sub = false;
    var _loading = false;

    function $(id) { return document.getElementById(id); }
    function log(msg) { console.log('[Push]', msg); }

    function updateUI() {
        var s = $('pushStatus'), d = $('pushDesc'), k = $('pushKnob'), t = $('pushToggle'), dot = $('pushDot');
        if (!s) return;
        if (_sub) {
            s.textContent = 'Aktif';
            d.textContent = 'Menerima notifikasi';
            k.style.transform = 'translateX(22px)';
            t.classList.remove('bg-gray-300');
            t.classList.add('bg-primary');
            dot.classList.remove('hidden');
        } else {
            s.textContent = 'Nonaktif';
            d.textContent = 'Klik untuk mengaktifkan';
            k.style.transform = 'translateX(0)';
            t.classList.remove('bg-primary');
            t.classList.add('bg-gray-300');
            dot.classList.add('hidden');
        }
    }

    function showErr(msg) { var e = $('pushError'); if(e){e.textContent=msg;e.classList.remove('hidden');} log('Error: '+msg); }
    function showDbg(msg) { var d = $('pushDebug'); if(d){d.textContent=msg;d.classList.remove('hidden');} log(msg); }
    function hideMsgs() { var e=$('pushError'),d=$('pushDebug'); if(e)e.classList.add('hidden'); if(d)d.classList.add('hidden'); }

    function b64toArr(s) {
        var p='='.repeat((4-s.length%4)%4), b=(s+p).replace(/-/g,'+').replace(/_/g,'/'), r=atob(b), a=new Uint8Array(r.length);
        for(var i=0;i<r.length;i++) a[i]=r.charCodeAt(i);
        return a;
    }

    function apiUrl(path) {
        var base = (window.PUSH_API_URL || '').replace(/\/$/, '');
        return base + path;
    }

    function getAuthHeaders() {
        var headers = {'Content-Type':'application/json','Accept':'application/json'};
        if (window.PUSH_AUTH_TOKEN) {
            headers['Authorization'] = 'Bearer ' + window.PUSH_AUTH_TOKEN;
        } else {
            var meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) headers['X-CSRF-TOKEN'] = meta.content;
        }
        return headers;
    }

    window.pushToggle = async function() {
        if (_loading) return;
        _loading = true;
        hideMsgs();
        $('pushToggle').disabled = true;

        try {
            if (_sub) {
                showDbg('Menonaktifkan...');
                var reg = await navigator.serviceWorker.ready;
                var sub = await reg.pushManager.getSubscription();
                if (sub) {
                    await fetch(apiUrl('/api/push/unsubscribe'), {
                        method: 'POST',
                        headers: getAuthHeaders(),
                        body: JSON.stringify({endpoint: sub.endpoint})
                    });
                    await sub.unsubscribe();
                }
                _sub = false;
                showDbg('Notifikasi dinonaktifkan.');
            } else {
                showDbg('Meminta izin...');
                var p = await Notification.requestPermission();
                log('Permission: ' + p);
                if (p !== 'granted') throw new Error('Izin notifikasi ditolak');

                showDbg('Mengambil VAPID key...');
                var vRes = await fetch(apiUrl('/api/push/vapid-key'), {
                    headers: {'Accept': 'application/json'}
                });
                var vData = await vRes.json();
                log('VAPID: ' + (vData.publicKey ? 'OK' : 'MISSING'));
                if (!vData.publicKey) throw new Error('VAPID key tidak tersedia');

                showDbg('Subscribing...');
                var reg = await navigator.serviceWorker.ready;
                var sub = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: b64toArr(vData.publicKey)
                });
                log('Subscribed: ' + sub.endpoint.substring(0,50));

                showDbg('Menyimpan ke server...');
                var sRes = await fetch(apiUrl('/api/push/subscribe'), {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify(sub.toJSON())
                });
                var sData = await sRes.json();
                log('Server: ' + sRes.status + ' ' + JSON.stringify(sData));
                if (!sRes.ok) throw new Error(sData.message || 'Server error ' + sRes.status);

                _sub = true;
                showDbg('Berhasil! Notifikasi aktif.');
            }
            updateUI();
        } catch(e) {
            showErr(e.message);
        }

        _loading = false;
        $('pushToggle').disabled = false;
    };

    // Init on load
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.ready.then(async function(reg) {
            var sub = await reg.pushManager.getSubscription();
            _sub = sub !== null;
            updateUI();
            log('Init: subscription=' + _sub);
        }).catch(function(e) { log('SW ready error: ' + e.message); });
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        var dd = document.getElementById('pushDropdown');
        if (!dd) return;
        if (!dd.contains(e.target) && !e.target.closest('[onclick*="pushDropdown"]')) {
            dd.classList.add('hidden');
        }
    });
})();
</script>
