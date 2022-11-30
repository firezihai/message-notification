<?php

declare(strict_types=1);

namespace MessageNotification\Store;

/**
 * token储存接口.
 */
interface StoreInterface
{
    public function set(array $app,array $token);

    public function get(array $app);
}
