<?php

declare(strict_types=1);

namespace MessageNotification;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                MessageInterface::class => Message::class,
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for message notification.',
                    'source' => __DIR__ . '/../publish/message_notification.php',
                    'destination' => BASE_PATH . '/config/autoload/message_notification.php',
                ],
            ],
        ];
    }
}
