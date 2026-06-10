<?php

namespace App\View\Composers;

use Illuminate\View\View;

class WarehouseComposer
{
    public function compose(View $view)
    {
        $menuItems = [
            ['path' => route('dashboard'), 'label' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard'],
            ['path' => route('pos.index'), 'label' => 'POS', 'icon' => 'shopping_cart', 'route' => 'pos.*'],
            ['divider' => 'Warehouse'],
            ['path' => route('warehouse.dashboard'), 'label' => 'WMS Dashboard', 'icon' => 'space_dashboard', 'route' => 'warehouse.dashboard'],
            ['path' => route('warehouse.register'), 'label' => 'Register Product', 'icon' => 'add_box', 'route' => 'warehouse.register'],
            ['path' => route('warehouse.stock'), 'label' => 'Stock Management', 'icon' => 'inventory_2', 'route' => 'warehouse.stock'],
            ['path' => route('warehouse.inbound'), 'label' => 'Inbound', 'icon' => 'move_to_inbox', 'route' => 'warehouse.inbound'],
            ['path' => route('warehouse.barang'), 'label' => 'Inventory', 'icon' => 'inventory_2', 'route' => 'warehouse.barang'],
            ['path' => route('warehouse.active-tasks'), 'label' => 'Active Tasks', 'icon' => 'assignment_turned_in', 'route' => 'warehouse.active-tasks'],
            ['path' => route('warehouse.forklift'), 'label' => 'Forklift Tasks', 'icon' => 'forklift', 'route' => 'warehouse.forklift'],
            ['path' => route('warehouse.generate-barcode'), 'label' => 'Barcode Generator', 'icon' => 'qr_code_2', 'route' => 'warehouse.generate-barcode'],
            ['path' => route('warehouse.opname'), 'label' => 'Stock Opname', 'icon' => 'barcode_scanner', 'route' => 'warehouse.opname'],
            ['path' => route('warehouse.outbound-orders'), 'label' => 'Outbound Orders', 'icon' => 'output', 'route' => 'warehouse.outbound-orders'],
            ['path' => route('warehouse.prepare-barang'), 'label' => 'Prepare Barang', 'icon' => 'front_loader', 'route' => 'warehouse.prepare-barang'],
            ['path' => route('warehouse.putaway'), 'label' => 'Putaway Process', 'icon' => 'warehouse', 'route' => 'warehouse.putaway'],
            ['path' => route('warehouse.selected-stock'), 'label' => 'Stock Detail', 'icon' => 'analytics', 'route' => 'warehouse.selected-stock'],
            ['path' => route('warehouse.split-barang'), 'label' => 'Outbound Split', 'icon' => 'call_split', 'route' => 'warehouse.split-barang'],
            ['path' => route('warehouse.work-orders'), 'label' => 'Work Orders', 'icon' => 'list_alt', 'route' => 'warehouse.work-orders'],
            ['divider' => 'Management'],
            ['path' => route('product.getTable'), 'label' => 'Products', 'icon' => 'package_2', 'route' => 'product.*'],
            ['path' => route('category.getTable'), 'label' => 'Categories', 'icon' => 'category', 'route' => 'category.*'],
            ['path' => route('customer.getTable'), 'label' => 'Customers', 'icon' => 'group', 'route' => 'customer.*'],
            ['path' => route('salesorder.getTable'), 'label' => 'Sales Orders', 'icon' => 'receipt_long', 'route' => 'salesorder.*'],
            ['path' => route('user.getTable'), 'label' => 'Users', 'icon' => 'manage_accounts', 'route' => 'user.*'],
        ];

        $view->with('menuItems', $menuItems);
    }
}
