<?php

return [

    'trial_days' => env('LANGKAHKECIL_TRIAL_DAYS', 10),

    'affiliate' => [
        'register_bonus' => env('AFFILIATE_REGISTER_BONUS', 500),
        'upgrade_commission_rate' => env('AFFILIATE_UPGRADE_COMMISSION_RATE', 15),
        'max_discounts' => env('AFFILIATE_MAX_DISCOUNTS', 3),
        'max_discount_value' => env('AFFILIATE_MAX_discount_value', env('AFFILIATE_UPGRADE_COMMISSION_RATE', 15)),
        'max_discount_nominal' => env('AFFILIATE_MAX_DISCOUNT_NOMINAL', 10000),
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
