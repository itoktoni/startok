@auth
<div class="p-2 border-t border-base-300/20 relative">
    <button onclick="document.getElementById('profMenu').classList.toggle('hidden')"
        class="flex items-center gap-2 w-full px-2 py-1.5 rounded-md hover:bg-base-200 cursor-pointer">
        <div class="avatar placeholder">
            <div class="bg-primary text-primary-content w-7 rounded-full">
                <span class="text-xs">{{ auth()->user()->initials() }}</span>
            </div>
        </div>
        <div class="flex-1 min-w-0 text-left">
            <p class="font-medium text-sm truncate">{{ ucfirst(auth()->user()->role) }}</p>
            <p class="text-xs text-base-content/50 truncate">{{ auth()->user()->name }}</p>
        </div>
        <span class="icon-[tabler--dots-vertical] size-4 text-base-content/40"></span>
    </button>
    <div id="profMenu" onmouseleave="document.getElementById('profMenu').classList.add('hidden')" class="hidden absolute bottom-full left-2 right-2 mb-1 bg-base-100 border border-base-300/20 rounded-lg shadow-lg z-50 py-1">
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
