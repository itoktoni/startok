<x-sidebar>
    <x-slot:nav>
        <x-sidebar-item route="{{ route('dashboard') }}" icon="dashboard" label="Dashboard" />
        <x-sidebar-item route="{{ route('pos.index') }}" icon="shopping_cart" label="POS" />

        <div class="divider my-1 text-xs">Warehouse</div>
        <x-sidebar-item route="{{ route('warehouse.dashboard') }}" icon="space_dashboard" label="WMS Dashboard" />
        <x-sidebar-item route="{{ route('warehouse.register') }}" icon="add_box" label="Register Product" />
        <x-sidebar-item route="{{ route('warehouse.stock') }}" icon="inventory_2" label="Stock Management" />
        <x-sidebar-item route="{{ route('warehouse.inbound') }}" icon="move_to_inbox" label="Inbound" />
        <x-sidebar-item route="{{ route('warehouse.barang') }}" icon="inventory_2" label="Inventory" />
        <x-sidebar-item route="{{ route('warehouse.active-tasks') }}" icon="assignment_turned_in" label="Active Tasks" />
        <x-sidebar-item route="{{ route('warehouse.forklift') }}" icon="forklift" label="Forklift Tasks" />
        <x-sidebar-item route="{{ route('warehouse.generate-barcode') }}" icon="qr_code_2" label="Barcode Generator" />
        <x-sidebar-item route="{{ route('warehouse.opname') }}" icon="barcode_scanner" label="Stock Opname" />
        <x-sidebar-item route="{{ route('warehouse.outbound-orders') }}" icon="output" label="Outbound Orders" />
        <x-sidebar-item route="{{ route('warehouse.prepare-barang') }}" icon="front_loader" label="Prepare Barang" />
        <x-sidebar-item route="{{ route('warehouse.putaway') }}" icon="warehouse" label="Putaway Process" />
        <x-sidebar-item route="{{ route('warehouse.selected-stock') }}" icon="analytics" label="Stock Detail" />
        <x-sidebar-item route="{{ route('warehouse.split-barang') }}" icon="call_split" label="Outbound Split" />
        <x-sidebar-item route="{{ route('warehouse.work-orders') }}" icon="list_alt" label="Work Orders" />

        <div class="divider my-1 text-xs">Management</div>
        <x-sidebar-item route="{{ route('product.getTable') }}" icon="package_2" label="Products" />
        <x-sidebar-item route="{{ route('category.getTable') }}" icon="category" label="Categories" />
        <x-sidebar-item route="{{ route('customer.getTable') }}" icon="group" label="Customers" />
        <x-sidebar-item route="{{ route('salesorder.getTable') }}" icon="receipt_long" label="Sales Orders" />
        <x-sidebar-item route="{{ route('user.getTable') }}" icon="manage_accounts" label="Users" />

        <div class="divider my-1 text-xs">Settings</div>
        <x-sidebar-item route="{{ route('profile.edit') }}" icon="person" label="My Profile" />
        <x-sidebar-item route="{{ route('settings.env') }}" icon="settings" label="Environment" />
    </x-slot:nav>
</x-sidebar>
