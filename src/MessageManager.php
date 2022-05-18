<?php

declare(strict_types=1);

namespace MessageNotification;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use MessageNotification\Driver\DingTalk;
use MessageNotification\Driver\PlatformInterface;

class MessageManager
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    protected $drivers = [];

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(ConfigInterface $config, StdoutLoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function getDriver($name = 'default'): PlatformInterface
    {
        if (isset($this->drivers[$name]) && $this->drivers[$name] instanceof PlatformInterface) {
            return $this->drivers[$name];
        }

        $config = $this->config->get("message_notification.{$name}");
        if (empty($config)) {
            throw new \InvalidArgumentException(sprintf('The message notification config %s is invalid.', $name));
        }

        $driverClass = $config['driver'] ?? DingTalk::class;

        $driver = make($driverClass, ['config' => $config]);

        return $this->drivers[$name] = $driver;
    }
}
