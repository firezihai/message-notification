<?php

declare(strict_types=1);

namespace MessageNotification\Store;

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class Cache implements StoreInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * message notification config app 
     * @var array
     */
    protected $config;

    public function __construct(ContainerInterface $container, array $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    public function set(array $token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('token is empty');
        }
        $token['last_update_time'] = time();

        $cache = $this->container->get(CacheInterface::class);
        $cache->set($this->getCacheTokenKey(), $token);
    }

    public function get()
    {
        $cache = $this->container->get(CacheInterface::class);

        return $cache->get($this->getCacheTokenKey());
    }

    /**
     * Get the cache key of token.
     * @return string
     */
    protected function getCacheTokenKey()
    {
        return md5($this->config['appkey'] . 'access_token');
    }
}
