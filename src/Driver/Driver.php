<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

use Hyperf\Guzzle\ClientFactory;
use MessageNotification\Store\StoreInterface;
use Psr\Container\ContainerInterface;

/**
 * 平台驱动类.
 */
abstract class Driver implements PlatformInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $config;

    public function __construct(ContainerInterface $container, array $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * 获取client.
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        $clientFactory = make(ClientFactory::class);

        return $clientFactory->create(['verify' => false]);
    }

    public function getStore()
    {
        return make(StoreInterface::class, ['config' => [
            'appkey' => $this->config['app']['appkey'],
            'appsecret' => $this->config['app']['appsecret'],
            'driver' => $this->config['store']['driver'],
        ]]);
    }
}
