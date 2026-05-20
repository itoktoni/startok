<aside id="sb"
    class="fixed left-0 top-0 h-full w-[80vw] lg:w-56 bg-base-100 border-r border-base-300 z-[60] transition-transform -translate-x-full lg:translate-x-0 flex flex-col"
    onclick="event.stopPropagation()">
    <x-sidebar-header />
    <nav class="flex-1 p-2 space-y-0.5 overflow-y-auto">
        {{ $nav ?? '' }}
    </nav>
    <x-sidebar-profile />
</aside>
