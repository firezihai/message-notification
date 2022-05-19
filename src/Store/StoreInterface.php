<?php

declare(strict_types=1);

namespace MessageNotification\Store;

/**
 * token储存接口.
 */
interface StoreInterface
{
    public function set(array $token);

    public function get();
}
