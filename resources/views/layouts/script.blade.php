<script>
    function warehouseApp() {
        return {
            drawerOpen: false,
            sidebarOpen: true,
            unreadCount: 3,
            notifications: [
                { id: 1, icon: 'local_shipping', iconColor: 'text-primary', title: 'Inbound shipment #IN-2024-089 arrived at Dock 4', time: '5 min ago', read: false },
                { id: 2, icon: 'warning', iconColor: 'text-error', title: 'Low stock alert: Lithium Battery Packs (48V) — 12 units left', time: '23 min ago', read: false },
                { id: 3, icon: 'assignment_turned_in', iconColor: 'text-secondary', title: 'Putaway task #PW-9842 completed by J. Doe', time: '1 hour ago', read: false },
                { id: 4, icon: 'sync', iconColor: 'text-on-surface-variant', title: 'Stock relocation Zone C → Zone B finished', time: '2 hours ago', read: true },
                { id: 5, icon: 'person', iconColor: 'text-on-surface-variant', title: 'S. Lee started shift at 08:00 AM', time: '3 hours ago', read: true },
            ],
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('nav .bg-primary').forEach(function(el) {
            el.scrollIntoView({ block: 'center', behavior: 'smooth' });
        });
    });
    // Toast function for real-time notifications
    window.showToast = function(title, body) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-20 right-4 z-50 bg-surface-container-lowest border border-outline-variant rounded-lg p-4 shadow-lg max-w-sm';
        toast.innerHTML = '<div class="flex items-start gap-3"><span class="material-symbols-outlined text-primary">notifications</span><div><p class="font-body-sm font-semibold text-on-surface">' + title + '</p><p class="font-body-sm text-on-surface-variant text-sm">' + (body || '') + '</p></div></div>';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    };
</script>