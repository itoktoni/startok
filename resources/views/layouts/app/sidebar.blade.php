<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="shadcn">
<head>
    @include('partials.head')
</head>
<body class="bg-base-200 min-h-screen text-[13px]">
    <div id="ov" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="closeSB()"></div>

    {{-- Sidebar --}}
    <aside id="sb"
        class="fixed left-0 top-0 h-full w-[80vw] lg:w-56 bg-base-100 border-r border-base-300 z-[60] transition-transform -translate-x-full lg:translate-x-0 flex flex-col"
        onclick="event.stopPropagation()">
        <div class="p-3 pb-4 border-b border-base-300">
            <select class="select select-sm w-full">
                <option>{{ config('app.name', 'Laravel') }}</option>
            </select>
        </div>
        <nav class="flex-1 p-2 space-y-0.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-soft w-full justify-start gap-2" wire:navigate>
                <span class="icon-[tabler--dashboard] size-4"></span>
                <span class="text-sm">Dashboard</span>
            </a>
            <div class="divider my-1 text-xs">Menu</div>
            <a href="/product/table" class="btn btn-sm btn-soft w-full justify-start gap-2">
                <span class="icon-[tabler--package] size-4"></span>
                <span class="text-sm">Product</span>
            </a>
            {{ $sidebar ?? '' }}
            <div class="divider my-1 text-xs">System</div>
            <a href="#" class="btn btn-sm btn-soft w-full justify-start gap-2">
                <span class="icon-[tabler--settings] size-4"></span>
                <span class="text-sm">Settings</span>
            </a>
        </nav>
        @auth
        <div class="p-2 border-t border-base-300 relative">
            <button onclick="document.getElementById('profMenu').classList.toggle('hidden')"
                class="flex items-center gap-2 w-full px-2 py-1.5 rounded-md hover:bg-base-200 cursor-pointer">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content w-7 rounded-full">
                        <span class="text-xs">{{ auth()->user()->initials() }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="font-medium text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-base-content/50 truncate">{{ auth()->user()->email }}</p>
                </div>
                <span class="icon-[tabler--dots-vertical] size-4 text-base-content/40"></span>
            </button>
            <div id="profMenu" onmouseleave="document.getElementById('profMenu').classList.add('hidden')" class="hidden absolute bottom-full left-2 right-2 mb-1 bg-base-100 border border-base-300 rounded-lg shadow-lg z-50 py-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs hover:bg-base-200"><span class="icon-[tabler--user] size-4"></span>Profile</a>
                <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs hover:bg-base-200"><span class="icon-[tabler--settings] size-4"></span>Settings</a>
                <div class="border-t border-base-200 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-1.5 text-xs text-error hover:bg-base-200 w-full">
                        <span class="icon-[tabler--logout] size-4"></span>Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    {{-- Mobile top bar --}}
    <div class="lg:hidden fixed top-0 left-0 right-0 h-11 bg-base-100 border-b border-base-300 rounded-b-2xl py-7 flex items-center px-3 z-50">
        <button class="btn btn-xs py-4 px-2 rounded-3xl" onclick="toggleSB()">
            <span class="icon-[tabler--adjustments-alt] size-5"></span>
        </button>
        <span class="ml-2 font-semibold text-lg">{{ $title ?? config('app.name') }}</span>
        <div class="flex-1"></div>
    </div>

    {{-- Main content --}}
    <main class="lg:ml-56 pt-11 lg:pt-0 pb-28 lg:pb-12">
        <div class="p-2 lg:p-3 space-y-3">
            {{ $slot }}
        </div>
    </main>

    {{-- Mobile footer nav --}}
    <footer class="lg:hidden fixed bottom-0 left-0 right-0 bg-base-100 border-t border-base-300 z-40">
        <div class="flex justify-around items-center py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 -mt-4" wire:navigate>
                <span class="btn btn-circle btn-primary shadow-md"><span class="icon-[tabler--home] size-6"></span></span>
                <span class="text-[10px] mt-0.5">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-0.5 text-base-content/50">
                <span class="icon-[tabler--user] size-6"></span><span class="text-[10px]">Profile</span>
            </a>
        </div>
    </footer>

    <script>
        function toggleSB(){document.getElementById('sb').classList.toggle('-translate-x-full');document.getElementById('ov').classList.toggle('hidden')}
        function closeSB(){document.getElementById('sb').classList.add('-translate-x-full');document.getElementById('ov').classList.add('hidden')}
        document.querySelector('main').addEventListener('click',()=>{if(innerWidth<1024)closeSB()});
    </script>
</body>
</html>
