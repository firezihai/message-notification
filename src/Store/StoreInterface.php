<?php

declare(strict_types=1);

namespace MessageNotification\Store;

interface StoreInterface
{
    public function set(array $token);

    public function get();
}
