<?php

declare(strict_types=1);

namespace MessageNotification\Driver;

/**
 * 钉钉自定义机器人消息.
 */
class DingTalkRobot extends Driver
{
    private $api = 'https://oapi.dingtalk.com/robot/send';

    public function send(array $userId, string $message)
    {
        $url = $this->api . '?access_token=' . $this->getAccessToken();
        // 需要签名
        if (isset($this->config['app']['appsecret']) && ! empty($this->config['app']['appsecret'])) {
            $timestamp = time() * 1000;
            $signString = $timestamp . "\n" . $this->config['app']['appsecret'];

            $sign = hash_hmac('sha256', $signString, $this->config['app']['appsecret'], true);
            $sign = urlencode(base64_encode($sign));
            $url .= '&timestamp=' . $timestamp . '&sign=' . $sign;
        }
        $client = $this->getClient();
        $response = $client->post($url, [
            'json' => [
                'msgtype' => 'markdown',
                'markdown' => [
                    'title' => $this->config['app']['app_name'],
                    'text' => '@' . join('@', $userId) . $message,
                ],
                'at' => [
                    'atUserIds' => $userId,
                ],
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if (! isset($response['errcode']) || $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        return $response;
    }

    public function getAccessToken()
    {
        if (! is_array($this->config['store'])) {
            throw new \InvalidArgumentException('The message notification config store must be an array ');
        }
        return $this->config['store']['access_token'];
    }

    public function getUserIdByMobile(string $mobile)
    {
        return '';
    }
}
