<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Warehouse Menu
    |--------------------------------------------------------------------------
    |
    | Define menu items for desktop sidebar, mobile drawer, and bottom nav.
    | Each item: route (string), icon (string), label (string)
    | Sections: label (string), items (array)
    | Bottom nav: only 5 items max, uses short label
    |
    */

    'sidebar' => [
        [
            'label' => null,
            'items' => [
                ['route' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                ['route' => 'pos.index', 'icon' => 'shopping_cart', 'label' => 'POS'],
            ],
        ],
        [
            'label' => 'Warehouse',
            'items' => [
                ['route' => 'warehouse.dashboard', 'icon' => 'space_dashboard', 'label' => 'WMS Dashboard'],
                ['route' => 'warehouse.register', 'icon' => 'add_box', 'label' => 'Register Product'],
                ['route' => 'warehouse.stock', 'icon' => 'inventory_2', 'label' => 'Stock Management'],
                ['route' => 'warehouse.inbound', 'icon' => 'move_to_inbox', 'label' => 'Inbound'],
                ['route' => 'warehouse.barang', 'icon' => 'inventory_2', 'label' => 'Inventory'],
                ['route' => 'warehouse.active-tasks', 'icon' => 'assignment_turned_in', 'label' => 'Active Tasks'],
                ['route' => 'warehouse.forklift', 'icon' => 'forklift', 'label' => 'Forklift Tasks'],
                ['route' => 'warehouse.generate-barcode', 'icon' => 'qr_code_2', 'label' => 'Barcode Generator'],
                ['route' => 'warehouse.opname', 'icon' => 'barcode_scanner', 'label' => 'Stock Opname'],
                ['route' => 'warehouse.outbound-orders', 'icon' => 'output', 'label' => 'Outbound Orders'],
                ['route' => 'warehouse.prepare-barang', 'icon' => 'front_loader', 'label' => 'Prepare Barang'],
                ['route' => 'warehouse.putaway', 'icon' => 'warehouse', 'label' => 'Putaway Process'],
                ['route' => 'warehouse.selected-stock', 'icon' => 'analytics', 'label' => 'Stock Detail'],
                ['route' => 'warehouse.split-barang', 'icon' => 'call_split', 'label' => 'Outbound Split'],
                ['route' => 'warehouse.work-orders', 'icon' => 'list_alt', 'label' => 'Work Orders'],
            ],
        ],
        [
            'label' => 'Management',
            'items' => [
                ['route' => 'product.getTable', 'icon' => 'package_2', 'label' => 'Products'],
                ['route' => 'satuan.getTable', 'icon' => 'package_2', 'label' => 'Satuan'],
                ['route' => 'category.getTable', 'icon' => 'category', 'label' => 'Categories'],
                ['route' => 'customer.getTable', 'icon' => 'group', 'label' => 'Customers'],
                ['route' => 'salesorder.getTable', 'icon' => 'receipt_long', 'label' => 'Sales Orders'],
                ['route' => 'user.getTable', 'icon' => 'manage_accounts', 'label' => 'Users'],
            ],
        ],
        [
            'label' => 'Settings',
            'items' => [
                ['route' => 'profile.edit', 'icon' => 'person', 'label' => 'My Profile'],
                ['route' => 'settings.env', 'icon' => 'settings', 'label' => 'Environment'],
            ],
        ],
    ],

    'bottom_nav' => [
        ['route' => 'warehouse.stock', 'icon' => 'inventory_2', 'label' => 'Stock'],
        ['route' => 'warehouse.active-tasks', 'icon' => 'assignment_turned_in', 'label' => 'Tasks'],
        ['route' => 'warehouse.dashboard', 'icon' => 'home', 'label' => 'Home'],
        ['route' => 'warehouse.inbound', 'icon' => 'move_to_inbox', 'label' => 'Inbound'],
        ['route' => 'warehouse.outbound-orders', 'icon' => 'output', 'label' => 'Outbound'],
    ],

];
