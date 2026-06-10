<?php

return [

    'api_url' => env('PUSH_API_URL', ''),

    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@startok.com'),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],

];
