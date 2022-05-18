<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

interface PlatformInterface
{
    /**
     * 发送消息.
     */
    public function send(array $userId, string $message);

    /**
     *  根据手机号码获取userid.
     */
    public function getUserIdByMobile(string $mobile);

    /**
     * 获取token.
     */
    public function getAccessToken();
}
