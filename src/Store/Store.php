<?php

declare(strict_types=1);

namespace MessageNotification\Store;

class Store
{
    protected $driver;

    public function __construct(StoreManager $manager, array $config)
    {
        $this->driver = $manager->getDriver($config);
    }

    public function __call($name, $arguments)
    {
        return $this->driver->{$name}(...$arguments);
    }
}
