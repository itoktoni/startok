// POS Offline-First with Dexie.js
const db = new Dexie('POSOffline');
db.version(1).stores({
    orders: '++id, order_code, status, created_at',
    order_items: '++id, order_id, product_name'
});

// Online/Offline Status
let isOnline = navigator.onLine;
const statusEl = document.getElementById('onlineStatus');

function updateOnlineStatus() {
    isOnline = navigator.onLine;
    if (statusEl) {
        statusEl.textContent = isOnline ? 'Online' : 'Offline';
        statusEl.className = isOnline ? 'badge badge-success' : 'badge badge-error';
    }
}

window.addEventListener('online', () => { updateOnlineStatus(); syncOrders(); });
window.addEventListener('offline', () => { updateOnlineStatus(); });

// Save order locally
async function saveOrderLocal(data) {
    const orderCode = 'POS' + Date.now();
    const orderId = await db.orders.add({
        order_code: orderCode,
        data: JSON.stringify(data),
        status: 'pending',
        created_at: new Date().toISOString()
    });
    return orderCode;
}

// Sync pending orders to server
async function syncOrders() {
    if (!isOnline) return;

    const pending = await db.orders.where('status').equals('pending').toArray();
    for (const order of pending) {
        try {
            const res = await fetch('/pos-checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                },
                body: order.data
            });
            const result = await res.json();
            if (result.success) {
                await db.orders.update(order.id, { status: 'synced', server_order_id: result.order_id });
            }
        } catch (e) {
            console.log('Sync failed:', e);
        }
    }
}

// Get pending count
async function getPendingCount() {
    return await db.orders.where('status').equals('pending').count();
}
