<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'WMS Portal' }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body
    class="text-on-surface bg-surface antialiased font-body-sm"
    x-data="warehouseApp()"
>
    {{-- Overlay for mobile drawer --}}
    <div
        class="fixed inset-0 bg-black/40 z-40 md:hidden transition-opacity duration-200"
        :class="drawerOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        @click="drawerOpen = false"
    ></div>

    {{-- Header --}}
    <header class="fixed top-0 w-full z-50 bg-surface-container-lowest shadow-sm border-b border-outline-variant flex items-center justify-between px-4 md:px-8 h-16">
        <div class="flex items-center gap-4">
            <button
                class="md:hidden p-2 hover:bg-surface-container rounded-full transition-colors"
                @click="drawerOpen = !drawerOpen"
            >
                <span class="material-symbols-outlined text-on-surface-variant">menu</span>
            </button>
            <button
                class="hidden md:block p-2 hover:bg-surface-container rounded-full transition-colors"
                @click="sidebarOpen = !sidebarOpen"
            >
                <span class="material-symbols-outlined text-on-surface-variant">menu</span>
            </button>
            <a href="{{ route('warehouse.dashboard') }}" class="font-headline-md text-headline-md font-bold text-primary">
                WMS Portal
            </a>
        </div>

        <div class="flex items-center gap-2">
            {{-- Notification Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button
                    class="relative p-2 hover:bg-surface-container rounded-full transition-colors text-on-surface-variant"
                    @click="open = !open"
                >
                    <span class="material-symbols-outlined">notifications</span>
                    <span
                        class="absolute top-1 right-1 w-4 h-4 bg-error text-on-error text-[10px] font-bold rounded-full flex items-center justify-center"
                        x-show="unreadCount > 0"
                        x-text="unreadCount"
                    ></span>
                </button>

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="absolute right-0 top-full mt-2 w-80 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50"
                >
                    <div class="flex items-center justify-between px-4 py-3 border-b border-outline-variant">
                        <span class="font-headline-md text-headline-md text-on-surface">Notifications</span>
                        <button
                            class="font-label-caps text-label-caps text-primary hover:underline"
                            @click="unreadCount = 0"
                        >Mark all read</button>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div class="flex items-start gap-3 px-4 py-3 hover:bg-surface-container-low transition-colors border-b border-outline-variant/30 cursor-pointer"
                                 :class="{ 'bg-primary-fixed/10': !notif.read }">
                                <span class="material-symbols-outlined mt-0.5 shrink-0" :class="notif.iconColor" x-text="notif.icon"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-body-sm text-body-sm text-on-surface" :class="{ 'font-semibold': !notif.read }" x-text="notif.title"></p>
                                    <p class="font-label-caps text-label-caps text-on-surface-variant mt-0.5" x-text="notif.time"></p>
                                </div>
                                <div x-show="!notif.read" class="w-2 h-2 bg-primary rounded-full shrink-0 mt-2"></div>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-2 border-t border-outline-variant text-center">
                        <button class="font-label-caps text-label-caps text-primary hover:underline">View All Notifications</button>
                    </div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button
                    class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center overflow-hidden border border-outline-variant hover:ring-2 hover:ring-primary/20 transition-all"
                    @click="open = !open"
                >
                    <span class="material-symbols-outlined text-on-primary text-sm">person</span>
                </button>

                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="absolute right-0 top-full mt-2 w-64 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50"
                >
                    <div class="px-4 py-3 border-b border-outline-variant">
                        <p class="font-body-sm font-semibold text-on-surface">{{ auth()->user()->name ?? 'Warehouse Admin' }}</p>
                        <p class="font-label-caps text-label-caps text-on-surface-variant">{{ auth()->user()->email ?? 'admin@wms.com' }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-xl">person</span>
                            <span class="font-body-sm text-body-sm">My Profile</span>
                        </a>
                        <a href="{{ route('settings.env') }}" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-xl">settings</span>
                            <span class="font-body-sm text-body-sm">Settings</span>
                        </a>
                        <a href="#" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-on-surface">
                            <span class="material-symbols-outlined text-xl">help</span>
                            <span class="font-body-sm text-body-sm">Help & Support</span>
                        </a>
                    </div>
                    <div class="border-t border-outline-variant py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-error-container/30 transition-colors text-error">
                                <span class="material-symbols-outlined text-xl">logout</span>
                                <span class="font-body-sm text-body-sm font-semibold">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Drawer --}}
    <div
        class="fixed top-0 left-0 h-full w-72 bg-surface-container-lowest z-50 md:hidden shadow-2xl flex flex-col transition-transform duration-300"
        :class="drawerOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="flex items-center justify-between px-5 h-16 border-b border-outline-variant">
            <h2 class="font-headline-md text-headline-md font-bold text-primary">WMS Portal</h2>
            <button class="p-2 hover:bg-surface-container rounded-full transition-colors" @click="drawerOpen = false">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>
        <nav class="flex-1 py-4 px-3 pb-24 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">dashboard</span>
                <span class="font-body-sm">Dashboard</span>
            </a>
            <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pos.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('pos.*') ? 'text-primary' : 'text-on-surface-variant' }}">shopping_cart</span>
                <span class="font-body-sm">POS</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Warehouse</div>
            <a href="{{ route('warehouse.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.dashboard') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">space_dashboard</span>
                <span class="font-body-sm">WMS Dashboard</span>
            </a>
            <a href="{{ route('warehouse.register') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.register') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.register') ? 'text-primary' : 'text-on-surface-variant' }}">add_box</span>
                <span class="font-body-sm">Register Product</span>
            </a>
            <a href="{{ route('warehouse.stock') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.stock') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.stock') ? 'text-primary' : 'text-on-surface-variant' }}">inventory_2</span>
                <span class="font-body-sm">Stock Management</span>
            </a>
            <a href="{{ route('warehouse.inbound') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.inbound') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.inbound') ? 'text-primary' : 'text-on-surface-variant' }}">move_to_inbox</span>
                <span class="font-body-sm">Inbound</span>
            </a>
            <a href="{{ route('warehouse.barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.barang') ? 'text-primary' : 'text-on-surface-variant' }}">inventory_2</span>
                <span class="font-body-sm">Inventory</span>
            </a>
            <a href="{{ route('warehouse.active-tasks') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.active-tasks') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.active-tasks') ? 'text-primary' : 'text-on-surface-variant' }}">assignment_turned_in</span>
                <span class="font-body-sm">Active Tasks</span>
            </a>
            <a href="{{ route('warehouse.forklift') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.forklift') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.forklift') ? 'text-primary' : 'text-on-surface-variant' }}">forklift</span>
                <span class="font-body-sm">Forklift Tasks</span>
            </a>
            <a href="{{ route('warehouse.generate-barcode') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.generate-barcode') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.generate-barcode') ? 'text-primary' : 'text-on-surface-variant' }}">qr_code_2</span>
                <span class="font-body-sm">Barcode Generator</span>
            </a>
            <a href="{{ route('warehouse.opname') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.opname') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.opname') ? 'text-primary' : 'text-on-surface-variant' }}">barcode_scanner</span>
                <span class="font-body-sm">Stock Opname</span>
            </a>
            <a href="{{ route('warehouse.outbound-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.outbound-orders') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.outbound-orders') ? 'text-primary' : 'text-on-surface-variant' }}">output</span>
                <span class="font-body-sm">Outbound Orders</span>
            </a>
            <a href="{{ route('warehouse.prepare-barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.prepare-barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.prepare-barang') ? 'text-primary' : 'text-on-surface-variant' }}">front_loader</span>
                <span class="font-body-sm">Prepare Barang</span>
            </a>
            <a href="{{ route('warehouse.putaway') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.putaway') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.putaway') ? 'text-primary' : 'text-on-surface-variant' }}">warehouse</span>
                <span class="font-body-sm">Putaway Process</span>
            </a>
            <a href="{{ route('warehouse.selected-stock') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.selected-stock') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.selected-stock') ? 'text-primary' : 'text-on-surface-variant' }}">analytics</span>
                <span class="font-body-sm">Stock Detail</span>
            </a>
            <a href="{{ route('warehouse.split-barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.split-barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.split-barang') ? 'text-primary' : 'text-on-surface-variant' }}">call_split</span>
                <span class="font-body-sm">Outbound Split</span>
            </a>
            <a href="{{ route('warehouse.work-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('warehouse.work-orders') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.work-orders') ? 'text-primary' : 'text-on-surface-variant' }}">list_alt</span>
                <span class="font-body-sm">Work Orders</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Management</div>
            <a href="{{ route('product.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('product.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('product.*') ? 'text-primary' : 'text-on-surface-variant' }}">package_2</span>
                <span class="font-body-sm">Products</span>
            </a>
            <a href="{{ route('category.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('category.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('category.*') ? 'text-primary' : 'text-on-surface-variant' }}">category</span>
                <span class="font-body-sm">Categories</span>
            </a>
            <a href="{{ route('customer.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('customer.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.*') ? 'text-primary' : 'text-on-surface-variant' }}">group</span>
                <span class="font-body-sm">Customers</span>
            </a>
            <a href="{{ route('salesorder.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('salesorder.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('salesorder.*') ? 'text-primary' : 'text-on-surface-variant' }}">receipt_long</span>
                <span class="font-body-sm">Sales Orders</span>
            </a>
            <a href="{{ route('user.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('user.*') ? 'text-primary' : 'text-on-surface-variant' }}">manage_accounts</span>
                <span class="font-body-sm">Users</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Settings</div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('profile.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-on-surface-variant' }}">person</span>
                <span class="font-body-sm">My Profile</span>
            </a>
            <a href="{{ route('settings.env') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('settings.env') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}" @click="drawerOpen = false">
                <span class="material-symbols-outlined {{ request()->routeIs('settings.env') ? 'text-primary' : 'text-on-surface-variant' }}">settings</span>
                <span class="font-body-sm">Environment</span>
            </a>
        </nav>
    </div>

    {{-- Desktop Sidebar --}}
    <aside
        class="hidden md:flex flex-col fixed top-16 left-0 h-[calc(100vh-4rem)] w-72 z-40 transition-transform duration-300 px-3 pt-4 border-r border-outline-variant/50 shadow-sm"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <nav class="flex-1 space-y-2 overflow-y-auto pr-3 pb-4 sidebar-scroll">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">dashboard</span>
                <span class="font-body-sm">Dashboard</span>
            </a>
            <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('pos.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('pos.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">shopping_cart</span>
                <span class="font-body-sm">POS</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Warehouse</div>
            <a href="{{ route('warehouse.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.dashboard') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.dashboard') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">space_dashboard</span>
                <span class="font-body-sm">WMS Dashboard</span>
            </a>
            <a href="{{ route('warehouse.register') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.register') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.register') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">add_box</span>
                <span class="font-body-sm">Register Product</span>
            </a>
            <a href="{{ route('warehouse.stock') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.stock') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.stock') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">inventory_2</span>
                <span class="font-body-sm">Stock Management</span>
            </a>
            <a href="{{ route('warehouse.inbound') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.inbound') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.inbound') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">move_to_inbox</span>
                <span class="font-body-sm">Inbound</span>
            </a>
            <a href="{{ route('warehouse.barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.barang') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">inventory_2</span>
                <span class="font-body-sm">Inventory</span>
            </a>
            <a href="{{ route('warehouse.active-tasks') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.active-tasks') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.active-tasks') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">assignment_turned_in</span>
                <span class="font-body-sm">Active Tasks</span>
            </a>
            <a href="{{ route('warehouse.forklift') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.forklift') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.forklift') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">forklift</span>
                <span class="font-body-sm">Forklift Tasks</span>
            </a>
            <a href="{{ route('warehouse.generate-barcode') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.generate-barcode') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.generate-barcode') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">qr_code_2</span>
                <span class="font-body-sm">Barcode Generator</span>
            </a>
            <a href="{{ route('warehouse.opname') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.opname') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.opname') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">barcode_scanner</span>
                <span class="font-body-sm">Stock Opname</span>
            </a>
            <a href="{{ route('warehouse.outbound-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.outbound-orders') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.outbound-orders') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">output</span>
                <span class="font-body-sm">Outbound Orders</span>
            </a>
            <a href="{{ route('warehouse.prepare-barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.prepare-barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.prepare-barang') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">front_loader</span>
                <span class="font-body-sm">Prepare Barang</span>
            </a>
            <a href="{{ route('warehouse.putaway') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.putaway') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.putaway') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">warehouse</span>
                <span class="font-body-sm">Putaway Process</span>
            </a>
            <a href="{{ route('warehouse.selected-stock') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.selected-stock') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.selected-stock') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">analytics</span>
                <span class="font-body-sm">Stock Detail</span>
            </a>
            <a href="{{ route('warehouse.split-barang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.split-barang') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.split-barang') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">call_split</span>
                <span class="font-body-sm">Outbound Split</span>
            </a>
            <a href="{{ route('warehouse.work-orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouse.work-orders') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('warehouse.work-orders') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">list_alt</span>
                <span class="font-body-sm">Work Orders</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Management</div>
            <a href="{{ route('product.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('product.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('product.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">package_2</span>
                <span class="font-body-sm">Products</span>
            </a>
            <a href="{{ route('category.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('category.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('category.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">category</span>
                <span class="font-body-sm">Categories</span>
            </a>
            <a href="{{ route('customer.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('customer.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('customer.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">group</span>
                <span class="font-body-sm">Customers</span>
            </a>
            <a href="{{ route('salesorder.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('salesorder.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('salesorder.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">receipt_long</span>
                <span class="font-body-sm">Sales Orders</span>
            </a>
            <a href="{{ route('user.getTable') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('user.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('user.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">manage_accounts</span>
                <span class="font-body-sm">Users</span>
            </a>
            <div class="px-4 pt-4 pb-1 font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Settings</div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('profile.*') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">person</span>
                <span class="font-body-sm">My Profile</span>
            </a>
            <a href="{{ route('settings.env') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('settings.env') ? 'bg-primary-fixed text-primary font-semibold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('settings.env') ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">settings</span>
                <span class="font-body-sm">Environment</span>
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main
        class="pt-20 pb-32 md:pb-24 px-4 md:px-6"
        :class="sidebarOpen ? 'md:ml-72' : 'md:ml-0'"
    >
        <div class="max-w-full md:max-w-[calc(100vw-18rem)] mx-auto">
            {{ $slot }}
        </div>
    </main>

    {{-- Bottom Nav (Mobile) --}}
    <nav class="md:hidden fixed bottom-0 left-0 w-full h-16 bg-surface-container-lowest border-t border-outline-variant z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <div class="flex items-center justify-around h-full px-2">
            <a href="{{ route('warehouse.stock') }}" class="flex flex-col items-center justify-center transition-all flex-1 {{ request()->routeIs('warehouse.stock') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100' }}">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Stock</span>
            </a>
            <a href="{{ route('warehouse.active-tasks') }}" class="flex flex-col items-center justify-center transition-all flex-1 {{ request()->routeIs('warehouse.active-tasks') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100' }}">
                <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Tasks</span>
            </a>
            <div class="flex items-center justify-center flex-1 -mt-4">
                <a
                    href="{{ request()->routeIs('warehouse.dashboard') ? route('warehouse.register') : route('warehouse.dashboard') }}"
                    class="flex items-center justify-center bg-primary text-on-primary w-14 h-14 rounded-2xl shadow-lg ring-4 ring-surface-container-lowest active:scale-90 transition-all"
                >
                    <span class="material-symbols-outlined text-[28px]">
                        {{ request()->routeIs('warehouse.dashboard') ? 'add' : 'home' }}
                    </span>
                </a>
            </div>
            <a href="{{ route('warehouse.inbound') }}" class="flex flex-col items-center justify-center transition-all flex-1 {{ request()->routeIs('warehouse.inbound') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100' }}">
                <span class="material-symbols-outlined text-[24px]">move_to_inbox</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Inbound</span>
            </a>
            <a href="{{ route('warehouse.outbound-orders') }}" class="flex flex-col items-center justify-center transition-all flex-1 {{ request()->routeIs('warehouse.outbound-orders') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100' }}">
                <span class="material-symbols-outlined text-[24px]">output</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Outbound</span>
            </a>
        </div>
    </nav>

    @stack('scripts')

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
    </script>
</body>
</html>
