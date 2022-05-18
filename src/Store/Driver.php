<?php

declare(strict_types=1);

namespace MessageNotification\Store;

abstract class Driver implements StoreInterface
{
    /**
     * message notification config app 
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
