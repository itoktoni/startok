<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="shadcn">
<head>
    @include('partials.head')
</head>
<body class="bg-base-200 min-h-screen text-[13px]">
    <div id="ov" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="closeSB()"></div>

    @include('layouts.sidebar')

    <x-topbar :title="$title ?? null" />

    {{-- Main content --}}
    <main class="lg:ml-56 pt-11 lg:pt-0 pb-28 lg:pb-12">
        <div class="p-2 lg:p-3 space-y-3">
            {{ $slot }}
        </div>
    </main>

    @include('layouts.footer')
</script>

    <script>
        function toggleSB(){document.getElementById('sb').classList.toggle('-translate-x-full');document.getElementById('ov').classList.toggle('hidden')}
        function closeSB(){document.getElementById('sb').classList.add('-translate-x-full');document.getElementById('ov').classList.add('hidden')}
        document.querySelector('main').addEventListener('click',()=>{if(innerWidth<1024)closeSB()});

    </script>

    @include('layouts.alert')
</body>
</html>
