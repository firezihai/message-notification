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
     * message notification config app.
     * @var array
     */
    protected $config;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function set(array $app, array $token)
    {
        if (empty($token)) {
            throw new \InvalidArgumentException('token is empty');
        }
        $token['token_update_time'] = time();

        $cache = $this->container->get(CacheInterface::class);
        $cache->set($this->getCacheTokenKey($app), $token);
    }

    public function get(array $app)
    {
        $cache = $this->container->get(CacheInterface::class);

        return $cache->get($this->getCacheTokenKey($app));
    }

    /**
     * Get the cache key of token.
     * @param mixed $app
     * @return string
     */
    protected function getCacheTokenKey($app)
    {
        $key = $app['appkey'];
        $key .= $app['agent_id'] ?? '';
        return md5($key . 'access_token');
    }
}
