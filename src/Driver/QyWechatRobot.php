<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

class QyWechatRobot extends Driver
{
    private $api = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send';

    /**
     * {@inheritDoc}
     * @see \MessageNotification\Driver\PlatformInterface::getAccessToken()
     */
    public function getAccessToken()
    {
        if (! is_array($this->config['store'])) {
            throw new \InvalidArgumentException('The message notification config store must be an array ');
        }
        return $this->config['store']['access_token'];
    }

    /**
     * {@inheritDoc}
     * @see \MessageNotification\Driver\PlatformInterface::getUserIdByMobile()
     */
    public function getUserIdByMobile(string $mobile)
    {
        // TODO Auto-generated method stub
    }

    /**
     * {@inheritDoc}
     * @see \MessageNotification\Driver\PlatformInterface::send()
     */
    public function send(array $userId, string $message)
    {
        $url = $this->api . '?key=' . $this->getAccessToken();

        $client = $this->getClient();
        $response = $client->post($url, [
            'json' => [
                'msgtype' => 'markdown',
                'markdown' => [
                    'content' => '@' . join('@', $userId) . $message,
                ],
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return $response;
    }
}
