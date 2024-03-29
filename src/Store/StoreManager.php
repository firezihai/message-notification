<?php

declare(strict_types=1);

namespace MessageNotification\Store;

use Hyperf\Contract\StdoutLoggerInterface;

/**
 * token储存驱动管理类.
 */
class StoreManager
{
    protected $drivers = [];

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getDriver($config): StoreInterface
    {
        $driverClass = $config['driver'] ?? Cache::class;
        if (isset($this->drivers[$driverClass]) && $this->drivers[$driverClass] instanceof StoreInterface) {
            return $this->drivers[$driverClass];
        }
        $driver = make($driverClass);
        return $this->drivers[$driverClass] = $driver;
    }
}
