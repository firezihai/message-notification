<?php

declare(strict_types=1);

return [
    'default' => [
        'driver' => MessageNotification\Driver\DingTalk::class,
        'store' => [
            'driver' => MessageNotification\Store\Cache::class,
        ],
        'app' => [
            'appkey' => '',
            'appsecret' => '',
            'agent_id' => '',
        ],
    ],
];
