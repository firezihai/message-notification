<?php

declare(strict_types=1);

namespace MessageNotification;

/**
 * 消息类.
 */
class Message
{
    protected $driver;

    public function __construct(MessageManager $manager)
    {
        $this->driver = $manager->getDriver();
    }

    public function __call($name, $arguments)
    {
        return $this->driver->{$name}(...$arguments);
    }
}
