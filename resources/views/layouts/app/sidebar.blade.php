<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="shadcn">
<head>
    @include('partials.head')
</head>
<body class="bg-base-200 min-h-screen text-[13px]">
    <div id="ov" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="closeSB()"></div>

    <x-sidebar>
        <x-slot:nav>
            <x-sidebar-item route="{{ route('dashboard') }}" icon="dashboard" label="Dashboard" />
            <div class="divider my-1 text-xs">Menu</div>
            <x-sidebar-item route="{{ route('product.getTable') }}" icon="package" label="Product" />
            <x-sidebar-item route="{{ route('category.getTable') }}" icon="package" label="Category" />
            <div class="divider my-1 text-xs">System</div>
            <x-sidebar-item route="#" icon="settings" label="Settings" />
        </x-slot:nav>
    </x-sidebar>

    <x-topbar :title="$title" />

    {{-- Main content --}}
    <main class="lg:ml-56 pt-11 lg:pt-0 pb-28 lg:pb-12">
        <div class="p-2 lg:p-3 space-y-3">
            {{ $slot }}
        </div>
    </main>

    <x-bottombar>
        <x-bottombar-item route="{{ route('dashboard') }}" icon="home" label="Home" />
        <x-bottombar-item route="{{ route('profile.edit') }}" icon="user" label="Profile" />
        <x-bottombar-item route="{{ route('profile.edit') }}" icon="settings" label="Setting" />
    </x-bottombar>
</script>

    <script>
        function toggleSB(){document.getElementById('sb').classList.toggle('-translate-x-full');document.getElementById('ov').classList.toggle('hidden')}
        function closeSB(){document.getElementById('sb').classList.add('-translate-x-full');document.getElementById('ov').classList.add('hidden')}
        document.querySelector('main').addEventListener('click',()=>{if(innerWidth<1024)closeSB()});

    </script>

    @if ($errors->any())
        <link href="/vendor/flasher/flasher.min.css" rel="stylesheet">
        <script src="/vendor/flasher/flasher.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @foreach ($errors->all() as $error)
                    flasher.error("{{ $error }}");
                @endforeach
            });
        </script>
    @endif
</body>
</html>
