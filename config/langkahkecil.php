<?php

return [

    'trial_days' => env('LANGKAHKECIL_TRIAL_DAYS', 10),

    'affiliate' => [
        'register_bonus' => env('AFFILIATE_REGISTER_BONUS', 500),
        'upgrade_commission_rate' => env('AFFILIATE_UPGRADE_COMMISSION_RATE', 15),
        'upgrade_commission_bonus' => env('AFFILIATE_UPGRADE_COMMISSION_BONUS', 1000),
        'customer_discount_rate' => env('AFFILIATE_CUSTOMER_DISCOUNT_RATE', 20),
    ],

    'cashout' => [
        'minimum' => env('LANGKAHKECIL_CASHOUT_MINIMUM', 50000),
        'admin_rate' => env('LANGKAHKECIL_CASHOUT_ADMIN_RATE', 3),
    ],

    'banks' => [
        ['code' => 'seabank', 'name' => 'SeaBank'],
        ['code' => 'bca', 'name' => 'BCA'],
        ['code' => 'mandiri', 'name' => 'Mandiri'],
        ['code' => 'gopay', 'name' => 'GoPay'],
        ['code' => 'blu', 'name' => 'blu by BCA Digital'],
    ],

];
