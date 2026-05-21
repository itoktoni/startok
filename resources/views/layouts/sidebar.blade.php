<x-sidebar>
    <x-slot:nav>
        <x-sidebar-item route="{{ route('dashboard') }}" icon="dashboard" label="Dashboard" />
        <div class="divider my-1 text-xs">Menu</div>
        <x-sidebar-item route="{{ route('user.getTable') }}" icon="user" label="User" />
        <x-sidebar-item route="{{ route('product.getTable') }}" icon="package" label="Product" />
        <x-sidebar-item route="{{ route('category.getTable') }}" icon="package" label="Category" />
        <div class="divider my-1 text-xs">System</div>
        <x-sidebar-item route="#" icon="settings" label="Settings" />
    </x-slot:nav>
</x-sidebar>