<?php

declare(strict_types=1);

namespace MessageNotification;

/**
 * 消息接口.
 */
interface MessageInterface
{
    /**
     * 发送消息.
     */
    public function send(array $userId, array $message);

    /**
     *  根据手机号码获取userid.
     */
    public function getUserIdByMobile(string $mobile);
}
