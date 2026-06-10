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
</script>